<?php
/**
 * Copyright © 2020 SOFT LOFT PTY, LLC. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace SoftLoft\SalesApi\Model\Validators;


use Magento\Sales\Model\Order;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class OrderValidator
 * @package SoftLoft\SalesApi\Model\Validators
 */
class OrderValidator extends ValidatorAbstract
{
    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepository;

    /**
     * OrderValidator constructor.
     * @param OrderRepositoryInterface $orderRepository
     */
    public function __construct(OrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    /**
     * Is valid
     * @return bool
     * @throws LocalizedException
     */
    public function isValid(): bool
    {
        // Entity id is null
        if (empty($this->context->getEntityId())) {
            $this->addError(__('Entity ID not specified.'));
            return false;
        }

        // Try loading the order
        try {
            $entityId = $this->context->getEntityId();
            $order = $this->orderRepository->get($entityId);
            if ($order->getEntityId() === null) {
                $this->addError(__('Order with ID = %1 doesn\'t exist', $entityId));
                return false;
            }
        } catch (NoSuchEntityException $exception) {
            $this->addError($exception->getMessage());
            return false;
        }

        // Can order edit
        if ($order->canUnhold()) {
            $this->addError(__('The order is not editable - it is on hold.'));
            return false;
        }

        // States
        $state = $order->getState();
        if ($state === Order::STATE_COMPLETE
            || $state === Order::STATE_CLOSED || $order->isCanceled()
            || $order->isPaymentReview()
        ) {
            $this->addError(
                __('The order is not editable - canceled, on payment review, has state "complete" or "closed".')
            );
            return false;
        }

        if ($order->getActionFlag(Order::ACTION_FLAG_EDIT)
            || !$order->getPayment()->getMethodInstance()->canEdit()
        ) {
            $this->addError(__('The order is not editable.'));
            return false;
        }

        return true;
    }

    /**
     * Retrieve errors
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
