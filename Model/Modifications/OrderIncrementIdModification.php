<?php
/**
 * Copyright © 2020 SOFT LOFT PTY, LLC. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace SoftLoft\SalesApi\Model\Modifications;

use SoftLoft\SalesApi\Api\Data\OrderEntityInterface;

/**
 * Class OrderIncrementIdModification
 * @package SoftLoft\SalesApi\Model\Modifications
 */
class OrderIncrementIdModification extends ModificationAbstract
{
    /**
     * Execute
     * @param OrderEntityInterface $orderEntity
     */
    public function execute(OrderEntityInterface $orderEntity): void
    {
        if ($orderEntity->getIncrementId() !== '') {
            try {
                $order = $this->getOrderById($orderEntity->getEntityId());
                $order->setIncrementId($orderEntity->getIncrementId());
                $this->saveOrder($order);
            } catch (\Exception $exception) {
                $this->addError($exception->getMessage());
            }
        }
    }
}
