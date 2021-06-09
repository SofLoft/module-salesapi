<?php
/**
 * Copyright © 2020 SOFT LOFT PTY, LLC. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace SoftLoft\SalesApi\Model\Service;

use Magento\Framework\Exception\InputException;
use Magento\Sales\Api\InvoiceRepositoryInterface;

use SoftLoft\SalesApi\Api\Data\InvoiceEntityInterface;
use SoftLoft\SalesApi\Api\InvoiceManagementInterface;

/**
 * Class InvoiceService
 * @package SoftLoft\SalesApi\Model\Service
 */
class InvoiceService implements InvoiceManagementInterface
{
    /** @var InvoiceRepositoryInterface */
    private $invoiceRepository;

    /**
     * InvoiceService constructor.
     * @param InvoiceRepositoryInterface $invoiceRepository
     */
    public function __construct(
        InvoiceRepositoryInterface $invoiceRepository
    ) {
        $this->invoiceRepository = $invoiceRepository;
    }

    /**
     * Performs persist operations for a specified order.
     *
     * @param InvoiceEntityInterface $entity The order data.
     * @return void
     * @throws InputException
     */
    public function update(InvoiceEntityInterface $entity) : void
    {
        if (!$entity->getEntityId()) {
            throw new InputException(__('Entity ID not specified.'));
        }

        if (!$entity->getIncrementId()) {
            throw new InputException(__('Increment ID not specified.'));
        }

        $invoice = $this->invoiceRepository->get($entity->getEntityId());
        $invoice->setIncrementId($entity->getIncrementId());

        $this->invoiceRepository->save($invoice);
    }
}
