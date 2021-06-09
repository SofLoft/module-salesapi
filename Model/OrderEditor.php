<?php
/**
 * Copyright © 2020 SOFT LOFT PTY, LLC. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace SoftLoft\SalesApi\Model;


use Magento\Catalog\Helper\Product;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\ObjectManagerInterface;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\Quote\Item;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderItemInterface;
use Magento\Sales\Model\AdminOrder\Create As AdminOrderCreate;
use Magento\Quote\Model\Quote\Item\ToOrderItem;

/**
 * Class OrderEditor
 * @package SoftLoft\SalesApi\Model
 */
class OrderEditor
{
    /** @var AdminOrderCreate */
    private $orderCreate;

    /** @var Quote */
    private $quote;

    /** @var OrderInterface */
    private $order;

    /** @var ObjectManagerInterface */
    private $objectManager;

    /** @var ToOrderItem */
    private $toOrderItem;

    /**
     * OrderEditor constructor.
     * @param AdminOrderCreate $orderCreate
     * @param ObjectManagerInterface $objectManager
     * @param ToOrderItem $toOrderItem
     * @param OrderInterface $order
     */
    public function __construct(
        AdminOrderCreate $orderCreate,
        ObjectManagerInterface $objectManager,
        ToOrderItem $toOrderItem,
        OrderInterface $order
    ) {
        $this->orderCreate = $orderCreate;
        $this->objectManager = $objectManager;
        $this->toOrderItem = $toOrderItem;
        $this->order = $order;
    }

    /**
     * Init quote from order
     * @return $this
     */
    public function initQuoteFromOrder() : self
    {
        if (empty($this->quote)) {
            $this->orderCreate->getSession()->clearStorage();
            $this->orderCreate->getSession()->setUseOldShippingMethod(true);
            $this->orderCreate->getSession()->setStoreId($this->order->getStoreId());
            $this->orderCreate->getSession()->setQuoteId($this->order->getQuoteId());

            /** @todo */
            //$this->orderCreate->initFromOrder($this->order);

            $this->quote = $this->orderCreate->getQuote();
            /**
             * init first billing address, need for virtual products
             */
            $this->orderCreate->getBillingAddress();
        }

        return $this;
    }

    /**
     * Returns quote
     * @return Quote
     */
    public function getQuote() : Quote
    {
        return $this->quote;
    }

    /**
     * Add new items
     * @param array $items
     * @return $this
     */
    public function addNewItems(array $items) : self
    {
        $items = $this->processFiles($items);
        $this->orderCreate->addProducts($items);

        $this->collectQuoteTotal();
        $this->saveQuote();

        /** @var Item $quoteItem */
        foreach ($this->getQuote()->getAllItems() as $quoteItem) {
            $this->moveItemFromQuoteToOrder($items, $quoteItem);
        }

        return $this;
    }

    /**
     * Move item from quote to the order
     * @param array $items
     * @param Item $quoteItem
     */
    private function moveItemFromQuoteToOrder(array $items, Item $quoteItem) : void
    {
        foreach ($items as $productId => $params) {
            if ((int)$quoteItem->getProduct()->getId() === $productId) {
                $quoteItem->setOriginalPrice($params['original_price']);
                $quoteItem->setBasePrice($params['original_price']);
                $quoteItem->checkData();
                $orderItem = $this->toOrderItem->convert($quoteItem);
                $this->order->addItem($orderItem);
            }
        }
    }

    /**
     * Update items of quote
     * @param array $items
     * @return $this
     * @throws LocalizedException
     */
    public function updateQuoteItems(array $items) : self
    {
        $items = $this->processFiles($items);
        $this->orderCreate->updateQuoteItems($items);

        $this->collectQuoteTotal();
        $this->saveQuote();

        $this->updateOrderItemsByQuote();

        return $this;
    }

    /**
     * Save changes
     * @return void
     */
    public function saveQuote() : void
    {
        $this->orderCreate->saveQuote();
    }

    /**
     * Collect quote total
     * @return $this
     */
    public function collectQuoteTotal() : self
    {
        $this->orderCreate->getQuote()->setTotalsCollectedFlag(false);
        $this->orderCreate->collectShippingRates();

        return $this;
    }

    /**
     * Update order items by quote
     * @return $this
     */
    private function updateOrderItemsByQuote() : self
    {
        /** @var Item $quoteItem */
        foreach ($this->getQuote()->getAllItems() as $quoteItem) {
            $orderItem = $this->toOrderItem->convert($quoteItem);
            $this->updateOrderItemByNew($orderItem);
        }

        return $this;
    }

    /**
     * Update order item by new order item
     * @param OrderItemInterface $orderItem
     * @return $this
     */
    private function updateOrderItemByNew(OrderItemInterface $orderItem) : self
    {
        foreach ($this->order->getItems() as $oldItem) {
            if ($oldItem->getSku() === $orderItem->getSku()) {
                $oldItem->addData($orderItem->getData());
            }
        }

        return $this;
    }

    /**
     * Process buyRequest file options of items
     *
     * @param array $items
     * @return array
     */
    private function processFiles(array $items) : array
    {
        /* @var $productHelper Product */
        $productHelper = $this->objectManager->get(Product::class);
        foreach ($items as $id => $item) {
            $buyRequest = new DataObject($item);
            $params = ['files_prefix' => 'item_' . $id . '_'];
            $buyRequest = $productHelper->addParamsToBuyRequest($buyRequest, $params);
            if ($buyRequest->hasData()) {
                $items[$id] = $buyRequest->toArray();
            }
        }

        return $items;
    }
}
