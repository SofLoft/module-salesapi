<?php
/**
 * Copyright © 2020 SOFT LOFT PTY, LLC. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace SoftLoft\SalesApi\Model;

use Magento\Framework\DataObject;

use SoftLoft\SalesApi\Api\Data\OrderEntityInterface;

/**
 * Class OrderEntity
 * @package SoftLoft\SalesApi\Model
 */
class OrderEntity extends DataObject implements OrderEntityInterface
{
    /**
     * Entity id
     * @return int
     */
    public function getEntityId() : int
    {
        return (int)$this->getData(OrderEntityInterface::ENTITY_ID);
    }

    /**
     * Set entity id
     * @param mixed $entityId
     * @return $this
     */
    public function setEntityId($entityId) : self
    {
        return $this->setData(OrderEntityInterface::ENTITY_ID, (int)$entityId);
    }

    /**
     * Payment description
     * @return string
     */
    public function getPaymentDescription() : string
    {
        return (string)$this->getData(OrderEntityInterface::PAYMENT_DESCRIPTION);
    }

    /**
     * Set payment description
     * @param mixed $paymentDescription
     * @return $this
     */
    public function setPaymentDescription($paymentDescription) : self
    {
        return $this->setData(OrderEntityInterface::PAYMENT_DESCRIPTION, strip_tags(trim($paymentDescription)));
    }

    /**
     * Gets items for the order entity.
     * @inheritdoc
     */
    public function getItems()
    {
        return $this->getData(OrderEntityInterface::ITEMS);
    }

    /**
     * Set items
     * @param mixed $items
     * @return $this
     */
    public function setItems($items) : self
    {
        return $this->setData(OrderEntityInterface::ITEMS, $items);
    }

    /**
     * Order increment id
     * @return string
     */
    public function getIncrementId() : string
    {
        return (string)$this->getData(OrderEntityInterface::INCREMENT_ID);
    }

    /**
     * Set increment order id
     * @param mixed $orderId
     * @return $this
     */
    public function setIncrementId($orderId) : self
    {
        return $this->setData(OrderEntityInterface::INCREMENT_ID, (string)$orderId);
    }

    /**
     * Set quote id
     * @param mixed $quoteId
     * @return $this
     */
    public function setQuoteId($quoteId) : self
    {
        return $this->setData(OrderEntityInterface::QUOTE_ID, (int)$quoteId);
    }

    /**
     * Returns quote id
     * @return int
     */
    public function getQuoteId() : int
    {
        return (int)$this->getData(OrderEntityInterface::QUOTE_ID);
    }

    /**
     * Returns status of the order
     * @return string
     */
    public function getStatus() : string
    {
        return (string)$this->getData(OrderEntityInterface::STATUS);
    }

    /**
     * Set status
     * @param string $status
     * @return $this
     */
    public function setStatus(string $status) : self
    {
        return $this->setData(OrderEntityInterface::STATUS, trim($status));
    }
}
