<?php
/**
 * Copyright © 2020 SOFT LOFT PTY, LLC. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace SoftLoft\SalesApi\Model;


use Magento\Framework\DataObject;

use SoftLoft\SalesApi\Api\Data\ItemEntityInterface;

/**
 * Class ItemEntity
 * @package SoftLoft\SalesApi\Model
 */
class ItemEntity extends DataObject implements ItemEntityInterface
{
    /**
     * Returns SKU
     * @return string
     */
    public function getSku() : string
    {
        return (string)$this->getData(ItemEntityInterface::SKU);
    }

    /**
     * Set SKU
     * {@inheritdoc}
     */
    public function setSku($sku)
    {
        return $this->setData(ItemEntityInterface::SKU, $sku);
    }

    /**
     * Get price
     * @inheritdoc
     */
    public function getPrice()
    {
        return $this->getData(ItemEntityInterface::PRICE);
    }

    /**
     * Set price
     * {@inheritdoc}
     */
    public function setPrice($price)
    {
        return $this->setData(ItemEntityInterface::PRICE, $price);
    }

    /**
     * Qty
     * @return int
     */
    public function getQty() : int
    {
        return (int)$this->getData(ItemEntityInterface::QTY);
    }

    /**
     * Set QTY
     * {@inheritdoc}
     */
    public function setQty($qty)
    {
        return $this->setData(ItemEntityInterface::QTY, (int)$qty);
    }

    /**
     * Remove
     * @return bool
     */
    public function getRemove() : bool
    {
        return (bool)$this->getData(ItemEntityInterface::REMOVE);
    }

    /**
     * Set remove flag
     * {@inheritdoc}
     */
    public function setRemove($remove)
    {
        return $this->setData(ItemEntityInterface::REMOVE, (bool)$remove);
    }

    /**
     * Is add item
     * @return bool
     */
    public function getAdd() : bool
    {
        return (bool)$this->getData(ItemEntityInterface::ADD);
    }

    /**
     * Set add flag
     * {@inheritdoc}
     */
    public function setAdd($add)
    {
        return $this->setData(ItemEntityInterface::ADD, $add);
    }
}
