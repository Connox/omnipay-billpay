<?php

namespace Omnipay\BillPay\Message\RequestData;

use Omnipay\BillPay\Message\AuthorizeRequest;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\AbstractRequest;
use SimpleXMLElement;

/**
 * Class BankAccountTrait.
 *
 * @author    Andreas Lange <andreas.lange@quillo.de>
 * @copyright 2016, Quillo GmbH
 * @license   MIT
 */
trait BankAccountTrait
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
     * Gets the account holder.
     *
     * @return string|null Account holder name.
     */
    public function getAccountHolder()
    {
        return $this->getParameter('accountHolder');
    }

    /**
     * Gets the account number.
     *
     * @return string|null Account number, generally IBAN for euro countries
     */
    public function getAccountNumber()
    {
        return $this->getParameter('accountNumber');
    }

    /**
     * Gets the account sort code
     *
     * @return string|null Sort code, generally the BIC
     */
    public function getSortCode()
    {
        return $this->getParameter('sortCode');
    }

    /**
     * Sets the account holder.
     *
     * @param string $value Account Holder
     *
     * @return AuthorizeRequest
     */
    public function setAccountHolder($value)
    {
        return $this->setParameter('accountHolder', $value);
    }

    /**
     * Sets the account number.
     *
     * @param string $value Account number, generally IBAN number
     *
     * @return AuthorizeRequest
     */
    public function setAccountNumber($value)
    {
        return $this->setParameter('accountNumber', $value);
    }

    /**
     * Sets the sort code.
     *
     * @param string $value Sort code, generally the BIC
     *
     * @return AuthorizeRequest
     */
    public function setSortCode($value)
    {
        return $this->setParameter('sortCode', $value);
    }

    /**
     * Appends the bank account information to the SimpleXMLElement.
     *
     * @param SimpleXMLElement $data
     *
     * @throws InvalidRequestException
     */
    protected function appendBankAccount(SimpleXMLElement $data)
    {
        if ($this->getPaymentMethod() === 'invoice') {
            return;
        }

        $data->addChild('bank_account');
        $data->bank_account[0]['accountholder'] = $this->getAccountHolder();
        $data->bank_account[0]['accountnumber'] = $this->getAccountNumber();
        $data->bank_account[0]['sortcode'] = $this->getSortCode();
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
