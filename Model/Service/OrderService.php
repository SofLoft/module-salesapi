<?php
/**
 * Copyright © 2020 SOFT LOFT PTY, LLC. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace SoftLoft\SalesApi\Model\Service;


use Magento\Framework\Exception\InputException;

use SoftLoft\SalesApi\Api\Data\OrderEntityInterface;
use SoftLoft\SalesApi\Api\OrderManagementInterface;

use SoftLoft\SalesApi\Model\Validator;
use SoftLoft\SalesApi\Model\ValidatorFactory;

use SoftLoft\SalesApi\Model\Modification;
use SoftLoft\SalesApi\Model\ModificationFactory;

/**
 * Class OrderService
 */
class OrderService implements OrderManagementInterface
{
    /** @var ValidatorFactory */
    private $validatorFactory;

    /** @var ModificationFactory  */
    private $modificationFactory;

    /**
     * OrderService constructor.
     * @param ValidatorFactory $validatorFactory
     * @param ModificationFactory $modificationFactory
     */
    public function __construct(
        ValidatorFactory $validatorFactory,
        ModificationFactory $modificationFactory
    ) {
        $this->validatorFactory = $validatorFactory;
        $this->modificationFactory = $modificationFactory;
    }

    /**
     * Updates order
     * @param OrderEntityInterface $entity
     * @return void
     * @throws InputException
     */
    public function update(OrderEntityInterface $entity) : void
    {
        /** @var Validator $validator */
        $validator = $this->validatorFactory->create(['context' => $entity]);
        if ($validator->isValid() === false) {
            throw new InputException(__(implode(', ', $validator->getErrors())));
        }

        /** @var Modification $modification */
        $modification = $this->modificationFactory->create(['context' => $entity]);
        $modification->execute();

        if (count($modification->getErrors()) > 0) {
            throw new InputException(__(implode(', ', $modification->getErrors())));
        }
    }
}
