<?php
/**
 * Copyright © 2020 SOFT LOFT PTY, LLC. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace SoftLoft\SalesApi\Model\Modifications;

use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\OrderRepositoryInterface;

use SoftLoft\SalesApi\Api\ModificationInterface;

/**
 * Class ModificationAbstract
 * @package SoftLoft\SalesApi\Model\Modifications
 */
abstract class ModificationAbstract implements ModificationInterface
{
    /** @var array */
    protected $errors = [];

    /** @var OrderRepositoryInterface */
    protected $orderRepository;

    /**
     * OrderIncrementIdModification constructor.
     * @param OrderRepositoryInterface $orderRepository
     */
    public function __construct(OrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    /**
     * Errors
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Load order by id
     * @param mixed $entityId
     * @return OrderInterface
     */
    protected function getOrderById($entityId) : OrderInterface
    {
        return $this->orderRepository->get($entityId);
    }

    /**
     * Save order
     * @param OrderInterface $order
     * @return $this
     */
    protected function saveOrder(OrderInterface $order) : self
    {
        $this->orderRepository->save($order);

        return $this;
    }

    /**
     * Add message
     * @param string $message
     * @return $this
     */
    protected function addError(string $message) : self
    {
        if (trim($message) !== '') {
            $this->errors[] = $message;
        }

        return $this;
    }
}
