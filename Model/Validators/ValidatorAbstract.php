<?php
/**
 * Copyright © 2020 SOFT LOFT PTY, LLC. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace SoftLoft\SalesApi\Model\Validators;

use SoftLoft\SalesApi\Api\ValidatorInterface;
use SoftLoft\SalesApi\Api\Data\OrderEntityInterface;

/**
 * Class ValidatorAbstract
 * @package SoftLoft\SalesApi\Model\Validators
 */
abstract class ValidatorAbstract implements ValidatorInterface
{
    /** @var OrderEntityInterface */
    protected $context;

    /** @var array */
    protected $errors = [];

    /**
     * Init
     * @param OrderEntityInterface $orderEntity
     * @return $this
     */
    public function init(OrderEntityInterface $orderEntity): ValidatorInterface
    {
        $this->context = $orderEntity;

        return $this;
    }

    /**
     * Add error
     * @param string $message
     * @return $this
     */
    protected function addError(string $message) : self
    {
        $this->errors[] = trim($message);

        return $this;
    }
}
