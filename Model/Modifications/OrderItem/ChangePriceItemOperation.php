<?php
/**
 * Copyright © 2020 SOFT LOFT PTY, LLC. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace SoftLoft\SalesApi\Model\Modifications\OrderItem;

use Magento\Framework\Exception\LocalizedException;

use Magento\Quote\Model\Quote\Item;
use SoftLoft\SalesApi\Api\Data\ItemEntityInterface;
use SoftLoft\SalesApi\Api\Data\OrderEntityInterface;

/**
 * Class ChangePriceItemOperation
 * @package SoftLoft\SalesApi\Model\Modifications\OrderItem
 */
class ChangePriceItemOperation extends OperationAbstract
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
     */
    protected function collectData(ItemEntityInterface $itemEntity): void
    {
        /** @var Item $quoteItem */
        foreach ($this->getQuote()->getAllItems() as $quoteItem) {
            if ($quoteItem->getSku() === $itemEntity->getSku()) {
                $this->addChangedItem(
                    $quoteItem->getId(),
                    [
                        'qty' => $itemEntity->getQty() ?: $quoteItem->getQty(),
                        'custom_price' => $itemEntity->getPrice(),
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
            && (int)$itemEntity->getPrice() > 0
        );
    }
}
