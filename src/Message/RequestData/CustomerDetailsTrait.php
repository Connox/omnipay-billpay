<?php

namespace Omnipay\BillPay\Message\RequestData;

use Omnipay\BillPay\Customer;
use Omnipay\BillPay\Message\AuthorizeRequest;
use Omnipay\Common\CreditCard;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\AbstractRequest;
use SimpleXMLElement;

/**
 * Class CustomerDetailsTrait
 *
 * @author    Andreas Lange <andreas.lange@connox.de>
 * @copyright 2016, Connox GmbH
 * @license   MIT
 */
trait CustomerDetailsTrait
{
    /**
     * Get the card.
     *
     * @return CreditCard
     *
     * @codeCoverageIgnore
     */
    abstract public function getCard();

    /**
     * Get the client IP address.
     *
     * @return string
     *
     * @codeCoverageIgnore
     */
    abstract public function getClientIp();

    /**
     * @param string $country
     *
     * @return string|null ISO-3166-1 Alpha3
     *
     * @codeCoverageIgnore
     */
    abstract public function getCountryCode($country);

    /**
     * @throws InvalidRequestException
     *
     * @return null|Customer
     */
    public function getCustomerDetails()
    {
        return $this->getParameter('customerDetails');
    }

    /**
     * Sets the customer detail information
     *
     * @param Customer $customer
     *
     * @return AuthorizeRequest
     */
    public function setCustomerDetails($customer)
    {
        return $this->setParameter('customerDetails', $customer);
    }

    /**
     * @param SimpleXMLElement $data
     *
     * @throws InvalidRequestException
     */
    protected function appendCustomerDetails(SimpleXMLElement $data)
    {
        $data->addChild('customer_details');
        $this->appendCustomerDetailsAdditional($data, $this->getCustomerDetails());
        $this->appendCustomerDetailsCard($data, $this->getCard());
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

    /**
     * Fills additional data
     *
     * @param SimpleXMLElement $data
     * @param Customer         $customer
     */
    private function appendCustomerDetailsAdditional(SimpleXMLElement $data, Customer $customer)
    {
        $data->customer_details[0]['customerid'] = $customer->getId();
        $data->customer_details[0]['customertype'] = $customer->getType();
        $data->customer_details[0]['language'] = $customer->getLanguage();
        $data->customer_details[0]['ip'] = $this->getClientIp();
        $data->customer_details[0]['customerGroup'] = $customer->getGroup();
    }

    /**
     * Fills the card information
     *
     * @param SimpleXMLElement $data
     * @param CreditCard       $card
     */
    private function appendCustomerDetailsCard(SimpleXMLElement $data, CreditCard $card)
    {
        $data->customer_details[0]['salutation'] = $card->getGender();
        $data->customer_details[0]['title'] = $card->getBillingTitle();
        $data->customer_details[0]['firstName'] = $card->getBillingFirstName();
        $data->customer_details[0]['lastName'] = $card->getBillingLastName();
        $data->customer_details[0]['street'] = $card->getBillingAddress1();
        $data->customer_details[0]['streetNo'] = null;
        $data->customer_details[0]['addressAddition'] = $card->getBillingAddress2();
        $data->customer_details[0]['zip'] = $card->getBillingPostcode();
        $data->customer_details[0]['city'] = $card->getBillingCity();
        $data->customer_details[0]['country'] = $this->getCountryCode($card->getBillingCountry());
        $data->customer_details[0]['email'] = $card->getEmail();
        $data->customer_details[0]['phone'] = $card->getBillingPhone();
        $data->customer_details[0]['cellPhone'] = null;
        $data->customer_details[0]['birthday'] = $card->getBirthday('Ymd');
    }
}
