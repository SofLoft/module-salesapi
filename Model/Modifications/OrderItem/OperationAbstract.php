<?php
/**
 * Copyright © 2020 SOFT LOFT PTY, LLC. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace SoftLoft\SalesApi\Model\Modifications\OrderItem;


use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Model\Quote;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;

use SoftLoft\SalesApi\Api\Data\OrderEntityInterface;
use SoftLoft\SalesApi\Api\OrderModification\OrderItemOperation;
use SoftLoft\SalesApi\Api\Data\ItemEntityInterface;
use SoftLoft\SalesApi\Model\OrderEditor;
use SoftLoft\SalesApi\Model\OrderEditorFactory;

/**
 * Class OperationAbstract
 * @package SoftLoft\SalesApi\Model\Modifications\OrderItem
 */
abstract class OperationAbstract implements OrderItemOperation
{
    /** @var string */
    private const BASE_ROW_TOTAL = 'base_row_total';

    /** @var string */
    private const ROW_TOTAL = 'row_total';

    /** @var string */
    private const BASE_ROW_TAX_TOTAL = 'base_row_tax_total';

    /** @var string */
    private const ROW_TAX_TOTAL = 'row_tax_total';

    /** @var string */
    private const BASE_ROW_DISCOUNT_AMOUNT = 'base_row_discount_amount';

    /** @var string */
    private const ROW_DISCOUNT_AMOUNT = 'row_discount_amount';

    /** @var array */
    private $totals = [];

    /** @var OrderRepositoryInterface */
    private $orderRepository;

    /** @var OrderEditor */
    private $orderEditor;

    /** @var OrderEditorFactory */
    private $orderEditorFactory;

    /** @var ProductRepositoryInterface */
    private $productRepository;

    /** @var array */
    private $changedItems = [];

    /** @var OrderInterface|null */
    protected $order;

    /**
     * OperationAbstract constructor.
     * @param OrderRepositoryInterface $orderRepository
     * @param ProductRepositoryInterface $productRepository
     * @param OrderEditorFactory $orderEditorFactory
     */
    public function __construct(
        OrderRepositoryInterface $orderRepository,
        ProductRepositoryInterface $productRepository,
        OrderEditorFactory $orderEditorFactory
    ) {
        $this->orderRepository = $orderRepository;
        $this->productRepository = $productRepository;
        $this->orderEditorFactory = $orderEditorFactory;
    }

    /**
     * Execute action
     * @param OrderEntityInterface $orderEntity
     */
    public function execute(OrderEntityInterface $orderEntity): void
    {
        if ($this->hasItemsChanged() === true) {
            $this->collectItemsTotals($this->order);
            $this->collectOrderTotals($this->order);
            $this->saveOrder($this->order);
        }
    }

    /**
     * Load product by SKU
     * @param string $sku
     * @return ProductInterface
     * @throws NoSuchEntityException
     */
    protected function getProductBySku(string $sku) : ProductInterface
    {
        return $this->productRepository->get($sku);
    }

    /**
     * Init order
     * @param OrderEntityInterface $orderEntity
     * @return void
     * @throws LocalizedException
     */
    protected function initOrder(OrderEntityInterface $orderEntity) : void
    {
        if (empty($this->order)) {
            $this->order = $this->orderRepository->get($orderEntity->getEntityId());
            $this->orderEditor = $this->orderEditorFactory->create(['order' => $this->order]);

            foreach ($orderEntity->getItems() as $itemEntity) {
                if ($this->hasItemChanged($itemEntity) === true) {
                    // Before the data will be collected we must have create quote
                    $this->orderEditor->initQuoteFromOrder();
                    $this->collectData($itemEntity);
                }
            }
        }
    }

    /**
     * Collect changed data
     * @param ItemEntityInterface $itemEntity
     */
    abstract protected function collectData(ItemEntityInterface $itemEntity) : void;

    /**
     * Has item changed
     * @param ItemEntityInterface $itemEntity
     * @return bool
     */
    abstract protected function hasItemChanged(ItemEntityInterface $itemEntity) : bool;

    /**
     * Returns items has been changed
     * @return array
     */
    protected function getItemsChanged() : array
    {
        return $this->changedItems;
    }

    /**
     * Add changed item to stack
     * @param mixed $itemId
     * @param array $params
     * @return $this
     */
    protected function addChangedItem($itemId, array $params) : self
    {
        $this->changedItems[$itemId] = $params;
        return $this;
    }

    /**
     * Has items changed
     * @return bool
     */
    protected function hasItemsChanged() :bool
    {
        return count($this->changedItems) > 0;
    }

    /**
     * Returns order editor
     * @return OrderEditor
     */
    protected function getOrderEditor() : OrderEditor
    {
        return $this->orderEditor;
    }

    /**
     * Returns quote
     * @return Quote
     */
    protected function getQuote() : Quote
    {
        return $this->getOrderEditor()->getQuote();
    }

    /**
     * Collect order totals
     * @param OrderInterface $order
     * @return $this
     */
    protected function collectItemsTotals(OrderInterface $order) : self
    {
        foreach ($order->getItems() as $orderItem) {
            if (!$orderItem->isDeleted()) {
                $this->addTotal(self::BASE_ROW_TOTAL, $orderItem->getBaseRowTotal());
                $this->addTotal(self::ROW_TOTAL, $orderItem->getRowTotal());

                $this->addTotal(self::BASE_ROW_TAX_TOTAL, $orderItem->getBaseTaxAmount());
                $this->addTotal(self::ROW_TAX_TOTAL, $orderItem->getTaxAmount());

                $this->addTotal(self::BASE_ROW_DISCOUNT_AMOUNT, $orderItem->getBaseDiscountAmount());
                $this->addTotal(self::ROW_DISCOUNT_AMOUNT, $orderItem->getDiscountAmount());
            }
        }

        return $this;
    }

    /**
     * Collect order totals
     * @param OrderInterface $order
     * @return $this
     */
    protected function collectOrderTotals(OrderInterface $order) : self
    {
        // Taxes: items, shipping
        $baseTotalTaxes = $this->getTotal(self::BASE_ROW_TAX_TOTAL) + $order->getBaseShippingTaxAmount();
        $totalTaxes = $this->getTotal(self::ROW_TAX_TOTAL) + $order->getShippingTaxAmount();

        // Discounts: items, shipping
        $baseTotalDiscount = $this->getTotal(self::BASE_ROW_DISCOUNT_AMOUNT)
            + $order->getBaseShippingDiscountAmount();
        $totalDiscount = $this->getTotal(self::ROW_DISCOUNT_AMOUNT)
            + $order->getShippingDiscountAmount();

        // Calc
        if (isset($this->totals[self::BASE_ROW_TOTAL], $this->totals[self::ROW_TOTAL])) {

            // Subtotal: without taxes, discounts
            $order->setBaseSubtotal($this->getTotal(self::BASE_ROW_TOTAL));
            $order->setSubtotal($this->getTotal(self::ROW_TOTAL));

            // Subtotal + taxes
            $order->setBaseSubtotalInclTax(
                $order->getBaseSubtotal() + $this->getTotal(self::BASE_ROW_TAX_TOTAL)
            );
            $order->setSubtotalInclTax(
                $order->getSubtotal() + $this->getTotal(self::ROW_TAX_TOTAL)
            );

            // Taxes: items + Shipping
            $order->setBaseTaxAmount($baseTotalTaxes);
            $order->setTaxAmount($totalTaxes);

            // Discounts: items + shipping
            $order->setBaseDiscountAmount($baseTotalDiscount);
            $order->setDiscountAmount($totalDiscount);

            $order->setBaseGrandTotal(
                ($order->getBaseSubtotal() + $order->getBaseShippingAmount() + $baseTotalTaxes) - $baseTotalDiscount
            );

            $order->setGrandTotal(
                ($order->getSubtotal() + $order->getShippingAmount() + $totalTaxes) - $totalDiscount
            );
        }

        return $this;
    }

    /**
     * Save order
     * @param OrderInterface $order
     * @return void
     */
    protected function saveOrder(OrderInterface $order) : void
    {
        $order->setQuoteId($this->getQuote()->getId());
        $this->orderRepository->save($order);
    }

    /**
     * Add total
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    private function addTotal(string $key, $value) : self
    {
        if (isset($this->totals[$key]) === false) {
            $this->totals[$key] = 0;
        }

        $this->totals[$key] += $value;

        return $this;
    }

    /**
     * Returns totals of items
     * @param string $key
     * @return mixed
     */
    private function getTotal(string $key)
    {
        return $this->totals[$key] ?? 0;
    }
}
