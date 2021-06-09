<?php
/**
 * Copyright © 2020 SOFT LOFT PTY, LLC. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace SoftLoft\SalesApi\Model\Modifications;


use Magento\Sales\Api\OrderRepositoryInterface;

use SoftLoft\SalesApi\Api\Data\OrderEntityInterface;
use SoftLoft\SalesApi\Api\OrderModification\OrderItemOperation;

/**
 * Class OrderItemsModification
 * @package SoftLoft\SalesApi\Model\Modifications
 */
class OrderItemsModification extends ModificationAbstract
{
    /** @var OrderItemOperation[] */
    private $orderItemOperations;

    /**
     * OrderItemsModification constructor.
     * @param OrderRepositoryInterface $orderRepository
     * @param OrderItemOperation[] $orderItemOperations
     */
    public function __construct(
        OrderRepositoryInterface $orderRepository,
        array $orderItemOperations
    ) {
        parent::__construct($orderRepository);

        $this->orderItemOperations = $orderItemOperations;
    }

    /**
     * Execute
     * @param OrderEntityInterface $orderEntity
     */
    public function execute(OrderEntityInterface $orderEntity): void
    {
        if (is_array($orderEntity->getItems()) && count($orderEntity->getItems()) > 0) {
            foreach ($this->orderItemOperations as $operation) {
                try {
                    $operation->execute($orderEntity);
                } catch (\Exception $exception) {
                    $this->addError($exception->getMessage());
                }
            }
        }
    }
}
