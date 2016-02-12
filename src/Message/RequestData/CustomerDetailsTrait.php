<?php

namespace Omnipay\BillPay\Message\RequestData;

use Omnipay\BillPay\Customer;
use Omnipay\BillPay\Message\AuthorizeRequest;
use Omnipay\Common\Exception\InvalidRequestException;
use SimpleXMLElement;

/**
 * Class CustomerDetailsTrait
 *
 * @package   Omnipay\BillPay
 * @author    Andreas Lange <andreas.lange@quillo.de>
 * @copyright 2016, Quillo GmbH
 * @license   MIT
 */
trait CustomerDetailsTrait
{
    /**
     * @return null|Customer
     * @throws InvalidRequestException
     */
    public function getCustomerDetails()
    {
        return method_exists($this, 'getParameter') ? $this->getParameter('customerDetails') : null;
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
        return method_exists($this, 'setParameter') ? $this->setParameter('customerDetails', $customer) : $this;
    }

    /**
     * @param SimpleXMLElement $data
     *
     * @throws InvalidRequestException
     */
    protected function appendCustomerDetails(SimpleXMLElement $data)
    {
        /** @var AuthorizeRequest $this */

        if (!$this instanceof AuthorizeRequest) {
            throw new InvalidRequestException('Trait can only be used inside instance of ' . AuthorizeRequest::class);
        }

        $card = $this->getCard();
        $customer = $this->getCustomerDetails();

        if ($card === null) {
            throw new InvalidRequestException('Credit card and customer object required for address details.');
        }

        if ($customer === null) {
            throw new InvalidRequestException('Customer object required for additional details not covered by card.');
        }

        $data->addChild('customer_details');
        $data->customer_details[0]['customerid'] = $customer->getId();
        $data->customer_details[0]['customertype'] = $customer->getType();
        $data->customer_details[0]['salutation'] = null;
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
        $data->customer_details[0]['language'] = $customer->getLanguage();
        $data->customer_details[0]['ip'] = $this->getClientIp();
        $data->customer_details[0]['customerGroup'] = $customer->getGroup();
    }
}
