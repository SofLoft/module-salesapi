<?php
/**
 * Copyright © 2020 SOFT LOFT PTY, LLC. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace SoftLoft\SalesApi\Api;

use SoftLoft\SalesApi\Api\Data\OrderEntityInterface;

/**
 * Interface ValidatorInterface
 * @package SoftLoft\SalesApi\Api
 * @api
 */
interface ValidatorInterface
{
    /**
     * Init
     * @param OrderEntityInterface $orderEntity
     * @return $this
     */
    public function init(OrderEntityInterface $orderEntity) : self;

    /**
     * Is valid
     * @return bool
     */
    public function isValid() : bool;

    /**
     * Retrieve errors
     * @return array
     */
    public function getErrors() : array;
}
