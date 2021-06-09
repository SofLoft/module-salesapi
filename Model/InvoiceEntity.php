<?php
/**
 * Copyright © 2020 SOFT LOFT PTY, LLC. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace SoftLoft\SalesApi\Model;

use Magento\Framework\DataObject;

use SoftLoft\SalesApi\Api\Data\InvoiceEntityInterface;

/**
 * Class InvoiceEntity
 * @package SoftLoft\SalesApi\Model
 */
class InvoiceEntity extends DataObject implements InvoiceEntityInterface
{
    /**
     * Gets the ID for the order entity.
     *
     * @return int|null Entity ID.
     */
    public function getEntityId()
    {
        return $this->getData(InvoiceEntityInterface::ENTITY_ID);
    }

    /**
     * Sets entity ID.
     *
     * @param int $entityId
     * @return $this
     */
    public function setEntityId($entityId)
    {
        return $this->setData(InvoiceEntityInterface::ENTITY_ID, $entityId);
    }

    /**
     * Set increment order id
     * @param string $orderId
     * @return $this
     */
    public function setIncrementId($orderId)
    {
        return $this->setData(InvoiceEntityInterface::INCREMENT_ID, $orderId);
    }

    /**
     * Order increment id
     * @return mixed
     */
    public function getIncrementId()
    {
        return $this->getData(InvoiceEntityInterface::INCREMENT_ID);
    }
}
