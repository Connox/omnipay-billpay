<?php

namespace Omnipay\BillPay\Message\ResponseData;

use SimpleXMLElement;

/**
 * Access invoice bank account in the response
 *
 * @author    Andreas Lange <andreas.lange@quillo.de>
 * @copyright 2016, Quillo GmbH
 * @license   MIT
 */
trait InvoiceBankAccountTrait
{
    /**
     * @return SimpleXMLElement
     */
    abstract public function getData();

    /**
     * Extracts the invoice bank account data if it exists
     *
     * @return array|null
     */
    public function getInvoiceBankAccount()
    {
        if (!$this->hasInvoiceBankAccount()) {
            return null;
        }

        return [
            'account_holder' => (string)$this->getData()->invoice_bank_account['account_holder'],
            'account_number' => (string)$this->getData()->invoice_bank_account['account_number'],
            'bank_code' => (string)$this->getData()->invoice_bank_account['bank_code'],
            'bank_name' => (string)$this->getData()->invoice_bank_account['bank_name'],
            'invoice_reference' => (string)$this->getData()->invoice_bank_account['invoice_reference'],
        ];
    }

    /**
     * Checks if the node has an invoice bank account node
     *
     * @return bool
     */
    public function hasInvoiceBankAccount()
    {
        return isset($this->getData()->invoice_bank_account);
    }
}
