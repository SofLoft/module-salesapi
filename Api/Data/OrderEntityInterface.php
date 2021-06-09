<?php
/**
 * Copyright © 2020 SOFT LOFT PTY, LLC. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace SoftLoft\SalesApi\Api\Data;

/**
 * Interface OrderEntityInterface
 */
interface OrderEntityInterface
{
    /** @var string */
    public const ENTITY_ID = 'entity_id';

    /** @var string */
    public const PAYMENT_DESCRIPTION = 'payment_description';

    /** @var string */
    public const ITEMS = 'items';

    /** @var string */
    public const INCREMENT_ID = 'increment_id';

    /** @var string */
    public const QUOTE_ID = 'quote_id';

    /** @var string */
    public const STATUS = 'status';

    /**
     * Gets the ID for the order entity.
     *
     * @return int Entity ID.
     */
    public function getEntityId() : int;

    /**
     * Sets entity ID.
     *
     * @param mixed $entityId
     * @return mixed
     */
    public function setEntityId($entityId);

    /**
     * Set increment order id
     * @param mixed $orderId
     * @return mixed
     */
    public function setIncrementId($orderId);

    /**
     * Order increment id
     * @return string
     */
    public function getIncrementId() : string;

    /**
     * Gets the payment description for the order entity.
     *
     * @return string Payment description.
     */
    public function getPaymentDescription() : string;

    /**
     * Sets payment description.
     *
     * @param string $paymentDescription
     * @return $this
     */
    public function setPaymentDescription($paymentDescription);

    /**
     * Gets items for the order entity.
     *
     * @return \SoftLoft\SalesApi\Api\Data\ItemEntityInterface[]|null Items.
     */
    public function getItems();

    /**
     * Sets items for the order entity.
     *
     * @param \SoftLoft\SalesApi\Api\Data\ItemEntityInterface[] $items
     * @return $this
     */
    public function setItems($items);

    /**
     * Set quote id
     * @param mixed $quoteId
     * @return mixed
     */
    public function setQuoteId($quoteId);

    /**
     * Returns quote id
     * @return int
     */
    public function getQuoteId() : int;

    /**
     * Returns status of the order
     * @return string
     */
    public function getStatus() : string;

    /**
     * Set status
     * @param string $status
     * @return mixed
     */
    public function setStatus(string $status);
}
