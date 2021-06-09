<?php
/**
 * Copyright © 2020 SOFT LOFT PTY, LLC. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace SoftLoft\SalesApi\Api\Data;

/**
 * Interface InvoiceEntityInterface
 */
interface InvoiceEntityInterface
{
    /** @var string */
    public const ENTITY_ID = 'entity_id';

    /** @var string */
    public const INCREMENT_ID = 'increment_id';

    /**
     * Gets the ID for the order entity.
     *
     * @return int|null Entity ID.
     */
    public function getEntityId();

    /**
     * Sets entity ID.
     *
     * @param int $entityId
     * @return $this
     */
    public function setEntityId($entityId);

    /**
     * Set increment order id
     * @param string $orderId
     * @return $this
     */
    public function setIncrementId($orderId);

    /**
     * Order increment id
     * @return mixed
     */
    public function getIncrementId();
}
