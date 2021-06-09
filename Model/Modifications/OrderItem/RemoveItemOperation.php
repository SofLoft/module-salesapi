<?php
/**
 * Copyright © 2020 SOFT LOFT PTY, LLC. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace SoftLoft\SalesApi\Model\Modifications\OrderItem;

use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Sales\Api\Data\OrderInterface;

use SoftLoft\SalesApi\Api\Data\ItemEntityInterface;
use SoftLoft\SalesApi\Api\Data\OrderEntityInterface;

/**
 * Class RemoveItemAction
 * @package SoftLoft\SalesApi\Model\Modifications\OrderItem
 */
class RemoveItemOperation extends OperationAbstract
{
    /**
     * Execute action
     * @param OrderEntityInterface $orderEntity
     * @throws InputException
     * @throws LocalizedException
     */
    public function execute(OrderEntityInterface $orderEntity): void
    {
        $this->initOrder($orderEntity);

        $itemsCount = count($this->order->getItems());
        foreach ($orderEntity->getItems() as $itemEntity) {
            if ($itemsCount > 1 && $itemEntity->getRemove() === true) {
                $this->removeItem($itemEntity, $this->order);
            } elseif ($itemsCount === 1 && $itemEntity->getRemove() === true) {
                throw new InputException(__('You cannot delete the last item of the order'));
            }
        }

        if ($this->hasItemsChanged()) {
            $this->getOrderEditor()->collectQuoteTotal();
            $this->getOrderEditor()->saveQuote();
        }

        parent::execute($orderEntity);
    }

    /**
     * Collect changed data
     * @param ItemEntityInterface $itemEntity
     */
    protected function collectData(ItemEntityInterface $itemEntity): void
    {
        foreach ($this->getQuote()->getAllItems() as $quoteItem) {
            if ($quoteItem->getSku() === $itemEntity->getSku()) {
                $this->addChangedItem(
                    $quoteItem->getId(),
                    ['action' => 'remove']
                );
                $quoteItem->isDeleted(true);
            }
        }
    }

    /**
     * Has be item changed
     * @param ItemEntityInterface $itemEntity
     * @return bool
     */
    protected function hasItemChanged(ItemEntityInterface $itemEntity): bool
    {
        return ($itemEntity->getSku() !== '' && $itemEntity->getRemove());
    }

    /**
     * Remove item from order
     * @param ItemEntityInterface $itemEntity
     * @param OrderInterface $order
     * @return $this
     */
    private function removeItem(
        ItemEntityInterface $itemEntity,
        OrderInterface $order
    ) : self {
        foreach ($order->getItems() as $item) {
            if ($item->getSku() === $itemEntity->getSku()) {
                $item->isDeleted(true);
            }
        }

        return $this;
    }
}
