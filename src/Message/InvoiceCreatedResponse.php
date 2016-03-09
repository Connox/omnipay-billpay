<?php

namespace Omnipay\BillPay\Message;

use Omnipay\BillPay\Message\ResponseData\BaseDataTrait;
use Omnipay\BillPay\Message\ResponseData\InvoiceBankAccountTrait;
use Omnipay\Common\Message\AbstractResponse;

/**
 * Class InvoiceCreatedResponse
 *
 * Example xml:
 * <code>
 * <?xml version="1.0" encoding="UTF-8" ?>
 * <data api_version="1.5.10" customer_message="" error_code="0" merchant_message="">
 *   <invoice_bank_account
 *     account_holder="BillPay GmbH"
 *     account_number="DE07312312312312312"
 *     bank_code="BELADEBEXXX"
 *     bank_name="Sparkasse Berlin"
 *     invoice_reference="BP555666777/9999"
 *     invoice_duedate="20150501"
 *     activation_performed="1"/>
 * </data>
 * </code>
 *
 * @author    Andreas Lange <andreas.lange@quillo.de>
 * @copyright 2016, Quillo GmbH
 * @license   MIT
 */
class InvoiceCreatedResponse extends AbstractResponse
{
    use BaseDataTrait;
    use InvoiceBankAccountTrait;
}
