<?php
/**
 * Copyright © 2020 SOFT LOFT PTY, LLC. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace SoftLoft\SalesApi\Model;

use SoftLoft\SalesApi\Api\Data\OrderEntityInterface;
use SoftLoft\SalesApi\Api\ModificationInterface;

/**
 * Class Modification
 * @package SoftLoft\SalesApi\Model
 */
class Modification
{
    /** @var OrderEntityInterface */
    private $context;

    /** @var ModificationInterface[]  */
    private $modifications;

    /** @var array */
    private $errors = [];

    /**
     * Modification constructor.
     * @param OrderEntityInterface $context
     * @param ModificationInterface[] $modifications
     */
    public function __construct(OrderEntityInterface $context, array $modifications)
    {
        $this->context = $context;
        $this->modifications = $modifications;
    }

    /**
     * Execute
     * @return void
     */
    public function execute(): void
    {
        foreach ($this->modifications as $modification) {
            $modification->execute($this->context);
            $this->readErrors($modification->getErrors());
        }
    }

    /**
     * Errors
     * @return array
     */
    public function getErrors() : array
    {
        return $this->errors;
    }

    /**
     * Read errors and set to general array
     * @param array $errors
     * @return $this
     */
    private function readErrors(array $errors) : self
    {
        foreach ($errors as $error) {
            $this->errors[] = trim($error);
        }

        return $this;
    }
}
