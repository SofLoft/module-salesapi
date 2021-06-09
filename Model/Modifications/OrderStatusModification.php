<?php
/**
 * Copyright © 2020 SOFT LOFT PTY, LLC. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace SoftLoft\SalesApi\Model\Modifications;

use Magento\Sales\Model\Order;
use SoftLoft\SalesApi\Api\Data\OrderEntityInterface;

/**
 * Class OrderStatusModification
 * @package SoftLoft\SalesApi\Model\Modifications
 */
class OrderStatusModification extends ModificationAbstract
{
    /**
     * Execute
     * @param OrderEntityInterface $orderEntity
     */
    public function execute(OrderEntityInterface $orderEntity): void
    {
        if ($orderEntity->getStatus() !== '') {
            try {
                $order = $this->getOrderById($orderEntity->getEntityId());
                if ($orderEntity->getStatus() === Order::STATE_CANCELED && $order->canCancel()) {
                    $order->cancel();
                } elseif ($orderEntity->getStatus() !== Order::STATE_CANCELED
                    && $order->getStatus() !== $orderEntity->getStatus()
                ) {
                    $order->addStatusHistoryComment(
                        __(
                            'Status has been changed by API. New status - %1',
                            $orderEntity->getStatus()),
                        $orderEntity->getStatus()
                    );
                }
                $this->saveOrder($order);
            } catch (\Exception $exception) {
                $this->addError($exception->getMessage());
            }
        }
    }
}
