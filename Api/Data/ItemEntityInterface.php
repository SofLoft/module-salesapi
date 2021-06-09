<?php


namespace SoftLoft\SalesApi\Api\Data;

use Magento\Framework\DataObject;
use Magento\Sales\Api\Data\EntityInterface;
use Magento\Sales\Api\Data\OrderInterface;

/**
 * Interface ItemEntityInterface
 */
interface ItemEntityInterface
{
    const SKU = 'sku';
    const PRICE = 'price';
    const QTY = 'qty';
    const REMOVE = 'remove';
    const ADD = 'add';

    /**
     * Gets SKU.
     *
     * @return string SKU.
     */
    public function getSku() : string;

    /**
     * Sets SKU.
     *
     * @param string $sku
     * @return $this
     */
    public function setSku($sku);

    /**
     * Gets price.
     *
     * @return float|null Price.
     */
    public function getPrice();

    /**
     * Sets price.
     *
     * @param float $price
     * @return $this
     */
    public function setPrice($price);

    /**
     * Gets qty.
     *
     * @return int Qty.
     */
    public function getQty() : int;

    /**
     * Sets qty.
     *
     * @param int $qty
     * @return $this
     */
    public function setQty($qty);

    /**
     * Gets need to remove item.
     *
     * @return bool Remove.
     */
    public function getRemove() : bool;

    /**
     * Sets need to remove item.
     *
     * @param mixed $remove
     * @return $this
     */
    public function setRemove($remove);

    /**
     * Gets need to add item.
     *
     * @return bool
     */
    public function getAdd() : bool;

    /**
     * Sets need to add item.
     *
     * @param mixed $add
     * @return $this
     */
    public function setAdd($add);
}
