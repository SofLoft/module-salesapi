<?php
/**
 * Copyright © 2020 SOFT LOFT PTY, LLC. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace SoftLoft\SalesApi\Model\Modifications\OrderItem;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

use SoftLoft\SalesApi\Api\Data\ItemEntityInterface;
use SoftLoft\SalesApi\Api\Data\OrderEntityInterface;

/**
 * Class AddItemOperation
 * @package SoftLoft\SalesApi\Model\Modifications\OrderItem
 */
class AddItemOperation extends OperationAbstract
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
            $this->getOrderEditor()->addNewItems($this->getItemsChanged());
        }

        parent::execute($orderEntity);
    }

    /**
     * Collect changed data
     * @param ItemEntityInterface $itemEntity
     * @throws NoSuchEntityException
     */
    protected function collectData(ItemEntityInterface $itemEntity): void
    {
        $qty = $itemEntity->getQty() ?: 1;
        $product = $this->getProductBySku($itemEntity->getSku());
        $this->addChangedItem(
            (int)$product->getId(),
            [
                'qty' => $qty,
                'original_price' => $product->getPrice(),
                'custom_price' => $itemEntity->getPrice() ?: $product->getPrice(),
                'use_discount' => true
            ]
        );
    }

    /**
     * Has item changed
     * @param ItemEntityInterface $itemEntity
     * @return bool
     */
    protected function hasItemChanged(ItemEntityInterface $itemEntity): bool
    {
        return ($itemEntity->getAdd() === true && $itemEntity->getSku() !== '');
    }
}
