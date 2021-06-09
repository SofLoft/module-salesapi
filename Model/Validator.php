<?php
/**
 * Copyright © 2020 SOFT LOFT PTY, LLC. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace SoftLoft\SalesApi\Model;

use SoftLoft\SalesApi\Api\Data\OrderEntityInterface;
use SoftLoft\SalesApi\Api\ValidatorInterface;

/**
 * Class ValidatorsFactory
 * @package SoftLoft\SalesApi\Model
 */
class Validator
{
    /** @var OrderEntityInterface */
    private $context;

    /** @var ValidatorInterface[] */
    private $validators;

    /** @var array */
    private $errors = [];

    /**
     * ValidatorsFactory constructor.
     * @param OrderEntityInterface $context
     * @param ValidatorInterface[] $validators
     */
    public function __construct(OrderEntityInterface $context, array $validators)
    {
        $this->context = $context;
        $this->validators = $validators;
    }

    /**
     * Is valid
     * @return bool
     */
    public function isValid() : bool
    {
        foreach ($this->validators as $validator) {
            if ($validator->init($this->context)->isValid() === false) {
                $this->errors = $validator->getErrors();
                return false;
            }
        }

        return true;
    }

    /**
     * Errors
     * @return array
     */
    public function getErrors() : array
    {
        return $this->errors;
    }
}
