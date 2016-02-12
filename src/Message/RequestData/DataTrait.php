<?php

namespace Omnipay\BillPay\Message\RequestData;

use Omnipay\BillPay\Message\AuthorizeRequest;
use Omnipay\Common\Exception\InvalidRequestException;
use SimpleXMLElement;

/**
 * Class DataTrait
 *
 * @package   Omnipay\BillPay
 * @author    Andreas Lange <andreas.lange@quillo.de>
 * @copyright 2016, Quillo GmbH
 * @license   MIT
 */
trait DataTrait
{
    private static $paymentTypes = [
        AuthorizeRequest::PAYMENT_TYPE_INVOICE => 1,
        AuthorizeRequest::PAYMENT_TYPE_DIRECT_DEBIT => 2,
        AuthorizeRequest::PAYMENT_TYPE_TRANSACTION_CREDIT => 3,
        AuthorizeRequest::PAYMENT_TYPE_PAY_LATER => 4,
        AuthorizeRequest::PAYMENT_TYPE_COLLATERAL_PROMISE => 7
    ];

    /**
     * @return int
     */
    public function getCaptureRequestNecessary()
    {
        return method_exists($this, 'getParameter') ? (int)$this->getParameter('captureRequestNecessary') : 0;
    }

    /**
     * Gets the expected delay in shipping
     *
     * @return int
     */
    public function getExpectedDaysTillShipping()
    {
        return method_exists($this, 'getParameter') ? (int)$this->getParameter('expectedDaysTillShipping') : 0;
    }

    /**
     * @param int|bool $value
     *
     * @return AuthorizeRequest
     */
    public function setCaptureRequestNecessary($value)
    {
        $value = $value ? 1 : 0;

        return method_exists($this, 'setParameter') ? $this->setParameter('captureRequestNecessary', $value) : $this;
    }

    /**
     * Sets the expected delay in shipping, required for authorize and pay
     *
     * @param int $value the expected delay in shipping
     *
     * @return AuthorizeRequest
     */
    public function setExpectedDaysTillShipping($value)
    {
        return method_exists($this, 'setParameter') ? $this->setParameter('expectedDaysTillShipping', $value) : $this;
    }

    /**
     * @param SimpleXMLElement $data
     *
     * @throws InvalidRequestException
     */
    protected function appendData(SimpleXMLElement $data)
    {
        /** @var AuthorizeRequest $this */

        if (!$this->getPaymentMethod()) {
            throw new InvalidRequestException('This request requires a payment method.');
        }

        // the customer has accepted the BillPay terms of service / the data protection policy, we assume that the
        // gateway is only used after acceptance
        $data['tcaccepted'] = 1;
        $data['expecteddaystillshipping'] = $this->getExpectedDaysTillShipping();
        $data['capturerequestnecessary'] = $this->getCaptureRequestNecessary();
        $data['paymenttype'] = self::$paymentTypes[$this->getPaymentMethod()];
    }
}
