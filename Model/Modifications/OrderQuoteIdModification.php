<?php
/**
 * Copyright © 2020 SOFT LOFT PTY, LLC. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace SoftLoft\SalesApi\Model\Modifications;


use SoftLoft\SalesApi\Api\Data\OrderEntityInterface;

/**
 * Class OrderQuoteIdModification
 * @package SoftLoft\SalesApi\Model\Modifications
 */
class OrderQuoteIdModification extends ModificationAbstract
{
    /**
     * Execute
     * @param OrderEntityInterface $orderEntity
     */
    public function execute(OrderEntityInterface $orderEntity): void
    {
        if ($orderEntity->getQuoteId() !== 0) {
            try {
                $order = $this->getOrderById($orderEntity->getEntityId());
                $order->setQuoteId($orderEntity->getQuoteId());
                $this->saveOrder($order);
            } catch (\Exception $exception) {
                $this->addError($exception);
            }
        }
    }
}
