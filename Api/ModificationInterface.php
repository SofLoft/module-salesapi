<?php
/**
 * Copyright © 2020 SOFT LOFT PTY, LLC. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace SoftLoft\SalesApi\Api;

use SoftLoft\SalesApi\Api\Data\OrderEntityInterface;

/**
 * Interface ModificationInterface
 * @package SoftLoft\SalesApi\Api
 * @api
 */
interface ModificationInterface
{
    /**
     * Execute
     * @param OrderEntityInterface $orderEntity
     */
    public function execute(OrderEntityInterface $orderEntity) : void;

    /**
     * Errors
     * @return array
     */
    public function getErrors() : array;
}
