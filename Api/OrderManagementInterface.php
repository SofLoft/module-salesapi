<?php
/**
 * Copyright © 2020 SOFT LOFT PTY, LLC. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace SoftLoft\SalesApi\Api;

use SoftLoft\SalesApi\Api\Data\OrderEntityInterface;

/**
 * Interface OrderManagementInterface
 * @api
 */
interface OrderManagementInterface
{
    /**
     * Performs persist operations for a specified order.
     *
     * @param Data\OrderEntityInterface $entity The order data.
     * @return void
     */
    public function update(OrderEntityInterface $entity) : void;
}
