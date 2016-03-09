<?php

namespace Omnipay\BillPay\Message\ResponseData;

use SimpleXMLElement;

/**
 * Access base data in the response, internal usage only
 *
 * @author    Andreas Lange <andreas.lange@quillo.de>
 * @copyright 2016, Quillo GmbH
 * @license   MIT
 */
trait BaseDataTrait
{
    /**
     * Response code
     *
     * @return null|string A response code from the payment gateway
     */
    public function getCode()
    {
        $data = $this->getData();

        return (string)$data['error_code'] ? : null;
    }

    /**
     * @return SimpleXMLElement
     */
    abstract public function getData();

    /**
     * Response Message
     *
     * @return null|string A response message from the payment gateway
     */
    public function getMessage()
    {
        $data = $this->getData();

        return (string)$data['customer_message'] ? : null;
    }

    /**
     * Status information of the response i.e. APPROVED
     *
     * @return null|string
     */
    public function getStatus()
    {
        $data = $this->getData();

        return (string)$data['status'] ? : null;
    }

    /**
     * Gateway Reference
     *
     * @return null|string A reference provided by the gateway to represent this transaction
     */
    public function getTransactionReference()
    {
        $data = $this->getData();

        return (string)$data['bptid'] ? : null;
    }

    /**
     * Is the response successful?
     *
     * @return bool
     */
    public function isSuccessful()
    {
        $data = $this->getData();

        return (string)$data['error_code'] === '0';
    }
}
