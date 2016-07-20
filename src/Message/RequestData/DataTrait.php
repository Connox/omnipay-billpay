<?php

namespace Omnipay\BillPay\Message\RequestData;

use Omnipay\BillPay\Message\AuthorizeRequest;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\AbstractRequest;
use SimpleXMLElement;

/**
 * Class DataTrait
 *
 * @author    Andreas Lange <andreas.lange@connox.de>
 * @copyright 2016, Connox GmbH
 * @license   MIT
 */
trait DataTrait
{
    private static $paymentTypes = [
        AuthorizeRequest::PAYMENT_TYPE_INVOICE => 1,
        AuthorizeRequest::PAYMENT_TYPE_DIRECT_DEBIT => 2,
        AuthorizeRequest::PAYMENT_TYPE_TRANSACTION_CREDIT => 3,
        AuthorizeRequest::PAYMENT_TYPE_PAY_LATER => 4,
        AuthorizeRequest::PAYMENT_TYPE_COLLATERAL_PROMISE => 7,
    ];

    /**
     * @return int
     */
    public function getCaptureRequestNecessary()
    {
        return (int)$this->getParameter('captureRequestNecessary');
    }

    /**
     * Gets the expected delay in shipping
     *
     * @return int
     */
    public function getExpectedDaysTillShipping()
    {
        return (int)$this->getParameter('expectedDaysTillShipping');
    }

    /**
     * Get the payment issuer.
     *
     * @return string
     *
     * @codeCoverageIgnore
     */
    abstract public function getPaymentMethod();

    /**
     * @param int|bool $value
     *
     * @return AuthorizeRequest
     */
    public function setCaptureRequestNecessary($value)
    {
        return $this->setParameter('captureRequestNecessary', $value ? 1 : 0);
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
        return $this->setParameter('expectedDaysTillShipping', $value);
    }

    /**
     * @param SimpleXMLElement $data
     *
     * @throws InvalidRequestException
     */
    protected function appendData(SimpleXMLElement $data)
    {
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

    /**
     * Get a single parameter.
     *
     * @param string $key The parameter key
     *
     * @return mixed
     *
     * @codeCoverageIgnore
     */
    abstract protected function getParameter($key);

    /**
     * Set a single parameter
     *
     * @param string $key   The parameter key
     * @param mixed  $value The value to set
     *
     * @return AbstractRequest Provides a fluent interface
     *
     * @codeCoverageIgnore
     */
    abstract protected function setParameter($key, $value);
}
