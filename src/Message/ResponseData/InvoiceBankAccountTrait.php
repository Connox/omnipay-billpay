<?php

namespace Omnipay\BillPay\Message\ResponseData;

use DateTime;
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
     *
     * @codeCoverageIgnore
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

        $data = $this->getData();

        return [
            'account_holder' => (string)$data->invoice_bank_account['account_holder'],
            'account_number' => (string)$data->invoice_bank_account['account_number'],
            'bank_code' => (string)$data->invoice_bank_account['bank_code'],
            'bank_name' => (string)$data->invoice_bank_account['bank_name'],
            'invoice_reference' => (string)$data->invoice_bank_account['invoice_reference'],
            'invoice_duedate' => $this->formatDate((string)$data->invoice_bank_account['invoice_duedate']),
            'activation_performed' => (string)$data->invoice_bank_account['activation_performed'] === '1',
        ];
    }

    /**
     * Checks if the node has an invoice bank account node
     *
     * @return bool
     */
    public function hasInvoiceBankAccount()
    {
        $data = $this->getData();

        return isset($data->invoice_bank_account) && (string)$data->invoice_bank_account['account_holder'] !== '';
    }

    /**
     * @param string $date Date with format 'Ymd'
     *
     * @return null|string Y-m-d or null
     */
    private function formatDate($date)
    {
        if (!$date) {
            return null;
        }

        return DateTime::createFromFormat('Ymd', $date)->format('Y-m-d');
    }
}
