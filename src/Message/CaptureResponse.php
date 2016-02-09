<?php

namespace Omnipay\BillPay\Message;

/**
 * Class CaptureResponse
 *
 * Example xml:
 * <code>
 * <?xml version="1.0" encoding="UTF-8"?>
 * <data customer_message="" error_code="0" merchant_message="">
 *   <invoice_bank_account account_holder="BillPay GmbH"
 *                         account_number="DE07312312312312312"
 *                         bank_code="BELADEBEXXX"
 *                         bank_name="Sparkasse Berlin"
 *                         invoice_reference="BP555666777/9999"/>
 * </data>
 * </code>
 *
 * @package   Omnipay\BillPay
 * @author    Andreas Lange <andreas.lange@quillo.de>
 * @copyright 2016, Quillo GmbH
 * @license   MIT
 */
class CaptureResponse extends Response
{
    /**
     * @return bool
     */
    public function hasInvoiceBankAccount()
    {
        return isset($this->data->invoice_bank_account);
    }

    /**
     * @return array|null
     */
    public function getInvoiceBankAccount()
    {
        if (!$this->hasInvoiceBankAccount())
        {
            return null;
        }

        return [
            'account_holder' => (string)$this->data->invoice_bank_account['account_holder'],
            'account_number' => (string)$this->data->invoice_bank_account['account_number'],
            'bank_code' => (string)$this->data->invoice_bank_account['bank_code'],
            'bank_name' => (string)$this->data->invoice_bank_account['bank_name'],
            'invoice_reference' => (string)$this->data->invoice_bank_account['invoice_reference']
        ];
    }
}

# vim :set ts=4 sw=4 sts=4 et :