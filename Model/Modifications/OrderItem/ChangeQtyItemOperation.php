<?php
/**
 * Copyright © 2020 SOFT LOFT PTY, LLC. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace SoftLoft\SalesApi\Model\Modifications\OrderItem;

use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

use Magento\Quote\Model\Quote\Item;
use SoftLoft\SalesApi\Api\Data\ItemEntityInterface;
use SoftLoft\SalesApi\Api\Data\OrderEntityInterface;

/**
 * Class ChangeQtyItemAction
 * @package SoftLoft\SalesApi\Model\Modifications\OrderItem
 */
class ChangeQtyItemOperation extends OperationAbstract
{
    /**
     * Execute action
     * @param OrderEntityInterface $orderEntity
     * @throws LocalizedException
     */
    public function execute(OrderEntityInterface $orderEntity): void
    {
        $this->initOrder($orderEntity);

        if ($this->hasItemsChanged()) {
            $this->getOrderEditor()->updateQuoteItems($this->getItemsChanged());
        }

        parent::execute($orderEntity);
    }

    /**
     * Collect changed data
     * @param ItemEntityInterface $itemEntity
     * @return void
     */
    protected function collectData(ItemEntityInterface $itemEntity): void
    {
        /** @var Item $item */
        foreach ($this->getQuote()->getAllItems() as $quoteItem) {
            if ($quoteItem->getSku() === $itemEntity->getSku()
                && $quoteItem->getQty() !== $itemEntity->getQty()
            ) {
                $this->addChangedItem(
                    $quoteItem->getId(),
                    [
                        'qty' => $itemEntity->getQty(),
                        'custom_price' => $itemEntity->getPrice() ?: $quoteItem->getCustomPrice(),
                        'use_discount' => true
                    ]
                );
            }
        }
    }

    /**
     * Has item changed
     * @param ItemEntityInterface $itemEntity
     * @return bool
     */
    protected function hasItemChanged(ItemEntityInterface $itemEntity): bool
    {
        return (
            $itemEntity->getRemove() === false
            && $itemEntity->getSku() !== ''
            && $itemEntity->getQty() > 0
        );
    }
}
