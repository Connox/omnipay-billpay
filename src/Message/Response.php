<?php

namespace Omnipay\BillPay\Message;

use Omnipay\Common\Message\AbstractResponse;

/**
 * BillPay Response
 *
 * @package   Omnipay\BillPay
 * @author    Andreas Lange <andreas.lange@quillo.de>
 * @copyright 2016, Quillo GmbH
 * @license   MIT
 */
class Response extends AbstractResponse
{
    /**
     * Response code
     *
     * @return null|string A response code from the payment gateway
     */
    public function getCode()
    {
        return (string)$this->data['error_code'] ? : null;
    }

    /**
     * Response Message
     *
     * @return null|string A response message from the payment gateway
     */
    public function getMessage()
    {
        return (string)$this->data['customer_message'] ? : null;
    }

    /**
     * Gateway Reference
     *
     * @return null|string A reference provided by the gateway to represent this transaction
     */
    public function getTransactionReference()
    {
        return (string)$this->data['bptid'] ? : null;
    }

    /**
     * Is the response successful?
     *
     * @return bool
     */
    public function isSuccessful()
    {
        return (string)$this->data['error_code'] === '0';
    }
}

# vim :set ts=4 sw=4 sts=4 et :