<?php

namespace Omnipay\BillPay\Message\RequestData;

use Omnipay\BillPay\Message\AuthorizeRequest;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\AbstractRequest;
use SimpleXMLElement;

/**
 * Class RateTrait.
 *
 * @author    Andreas Lange <andreas.lange@quillo.de>
 * @copyright 2016, Quillo GmbH
 * @license   MIT
 */
trait RateTrait
{
    /**
     * Get the payment issuer.
     *
     * @return string
     *
     * @codeCoverageIgnore
     */
    abstract public function getPaymentMethod();

    /**
     * Gets count of installments to be paid during the term.
     *
     * @return int|null Count of installments to be paid during the term.
     */
    public function getRateCount()
    {
        return $this->getParameter('rateCount');
    }

    /**
     * Gets term of the installment plan.
     *
     * @return int|null months
     */
    public function getRateTerm()
    {
        return $this->getParameter('rateTerm');
    }

    /**
     * Gets total amount of the installments plan including the transaction credit interest / PayLater fee.
     *
     * @return float|null adjusted amount including all fees and interest
     */
    public function getRateTotalAmount()
    {
        return $this->getParameter('rateTotalAmount');
    }

    /**
     * Sets count of installments to be paid during the term.
     *
     * @param string $value Count of installments to be paid during the term.
     *
     * @return AuthorizeRequest
     */
    public function setRateCount($value)
    {
        return $this->setParameter('rateCount', $value);
    }

    /**
     * Sets term of the installment plan.
     *
     * @param int $value months
     *
     * @return AuthorizeRequest
     */
    public function setRateTerm($value)
    {
        return $this->setParameter('rateTerm', $value);
    }

    /**
     * Sets total amount of the installments plan including the transaction credit interest / PayLater fee.
     *
     * @param float $value
     *
     * @return AuthorizeRequest
     */
    public function setRateTotalAmount($value)
    {
        return $this->setParameter('rateTotalAmount', $value);
    }

    /**
     * Appends the rate request node to the SimpleXMLElement.
     *
     * @param SimpleXMLElement $data
     *
     * @throws InvalidRequestException
     */
    protected function appendRate(SimpleXMLElement $data)
    {
        if ($this->getPaymentMethod() !== 'pay_later') {
            return;
        }

        $data->addChild('rate_request');
        $data->rate_request[0]['term'] = $this->getRateTerm();
        $data->rate_request[0]['ratecount'] = $this->getRateCount();
        $data->rate_request[0]['totalamount'] = round(bcmul($this->getRateTotalAmount(), 100, 8));
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
     * Set a single parameter.
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
