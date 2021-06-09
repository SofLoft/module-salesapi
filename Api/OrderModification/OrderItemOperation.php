<?php
/**
 * Copyright © 2020 SOFT LOFT PTY, LLC. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace SoftLoft\SalesApi\Api\OrderModification;

use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\NoSuchEntityException;
use SoftLoft\SalesApi\Api\Data\OrderEntityInterface;

/**
 * Interface OrderItemOperation
 * @package SoftLoft\SalesApi\Api\OrderModification
 * @api
 */
interface OrderItemOperation
{
    /**
     * Execute action
     * @param OrderEntityInterface $orderEntity
     * @throws InputException
     * @throws NoSuchEntityException
     */
    public function execute(OrderEntityInterface $orderEntity) : void;
}
