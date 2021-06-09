<?php
/**
 * Copyright © 2020 SOFT LOFT PTY, LLC. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace SoftLoft\SalesApi\Model\Modifications;

use SoftLoft\SalesApi\Api\Data\OrderEntityInterface;
use SoftLoft\OrderPaymentDescription\Model\Data\PaymentDescription;

/**
 * Class OrderPaymentDescriptionModification
 * @package SoftLoft\SalesApi\Model\Modifications
 */
class OrderPaymentDescriptionModification extends ModificationAbstract
{
    /**
     * Execute
     * @param OrderEntityInterface $orderEntity
     */
    public function execute(OrderEntityInterface $orderEntity): void
    {
        if ($orderEntity->getPaymentDescription() !== '') {
            try {
                $order = $this->getOrderById($orderEntity->getEntityId());
                $order->setData(
                    PaymentDescription::PAYMENT_DESCRIPTION_FIELD_NAME,
                    $orderEntity->getPaymentDescription()
                );
                $this->saveOrder($order);
            } catch (\Exception $exception) {
                $this->addError($exception);
            }
        }
    }
}
