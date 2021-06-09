<?php
/**
 * Copyright © 2020 SOFT LOFT PTY, LLC. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace SoftLoft\SalesApi\Api;

use SoftLoft\SalesApi\Api\Data\InvoiceEntityInterface;

/**
 * Interface InvoiceManagementInterface
 * @api
 */
interface InvoiceManagementInterface
{
    /**
     * Performs persist operations for a specified order.
     *
     * @param InvoiceEntityInterface $entity The order data.
     * @return void
     */
    public function update(InvoiceEntityInterface $entity) : void;
}
