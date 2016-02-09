<?php

namespace Omnipay\BillPay\Message;

use Omnipay\BillPay\Customer;
use Omnipay\BillPay\Item;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\ResponseInterface;
use SimpleXMLElement;

/**
 * Message AuthorizeRequest
 *
 * @link      https://techdocs.billpay.de/en/For_developers/XML_API/Preauthorize.html
 * @package   Omnipay\BillPay
 * @author    Andreas Lange <andreas.lange@quillo.de>
 * @copyright 2016, Quillo GmbH
 * @license   MIT
 */
class AuthorizeRequest extends AbstractRequest
{
    const PAYMENT_TYPE_INVOICE = 'invoice';
    const PAYMENT_TYPE_DIRECT_DEBIT = 'direct_debit';
    const PAYMENT_TYPE_TRANSACTION_CREDIT = 'transaction_credit';
    const PAYMENT_TYPE_PAY_LATER = 'pay_later';
    const PAYMENT_TYPE_COLLATERAL_PROMISE = 'collateral_promise';

    private static $paymentTypes = [
        self::PAYMENT_TYPE_INVOICE => 1,
        self::PAYMENT_TYPE_DIRECT_DEBIT => 2,
        self::PAYMENT_TYPE_TRANSACTION_CREDIT => 3,
        self::PAYMENT_TYPE_PAY_LATER => 4,
        self::PAYMENT_TYPE_COLLATERAL_PROMISE => 7
    ];

    /**
     * @return int
     */
    public function getCaptureRequestNecessary()
    {
        return (int)$this->getParameter('captureRequestNecessary');
    }

    /**
     * @return Customer|null
     */
    public function getCustomerDetails()
    {
        return $this->getParameter('customer');
    }

    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return SimpleXMLElement
     * @throws InvalidRequestException
     */
    public function getData()
    {
        if ($this->getCard() === null) {
            throw new InvalidRequestException('This request requires a credit card object and a customer object for address details.');
        }

        if ($this->getCustomerDetails() === null) {
            throw new InvalidRequestException('This request requires a customer object for additional details not covered by card.');
        }

        if ($this->getItems() === null || $this->getItems()->count() === 0) {
            throw new InvalidRequestException('This request requires items.');
        }

        if (!$this->getPaymentMethod()) {
            throw new InvalidRequestException('This request requires a payment method.');
        }

        $data = $this->getBaseData();

        // the customer has accepted the BillPay terms of service / the data protection policy, we assume that the
        // gateway is only used after acceptance
        $data['tcaccepted'] = 1;
        $data['expecteddaystillshipping'] = $this->getExpectedDaysTillShipping();
        $data['capturerequestnecessary'] = $this->getCaptureRequestNecessary();
        $data['paymenttype'] = self::$paymentTypes[$this->getPaymentMethod()];

        $this->appendCustomerDetails($data);
        $this->appendShippingDetails($data);
        $this->appendArticleData($data);

        return $data;
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
     * @param bool $value
     *
     * @return AuthorizeRequest
     */
    public function setCaptureRequestNecessary($value)
    {
        return $this->setParameter('captureRequestNecessary', $value ? 1 : 0);
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
        return $this->setParameter('customer', $customer);
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
        return $this->setParameter('expectedDaysTillShipping', (int)$value);
    }

    /**
     * Set the payment method.
     *
     * @param string $value
     *
     * @return AbstractRequest Provides a fluent interface
     * @throws InvalidRequestException
     * @see AuthorizeRequest::PAYMENT_TYPE_*
     */
    public function setPaymentMethod($value)
    {
        if ($value !== null && !array_key_exists($value, self::$paymentTypes)) {
            throw new InvalidRequestException(sprintf('Unknown payment method specified \'%s\' specified.', $value));
        }

        return parent::setPaymentMethod($value);
    }

    /**
     * @param SimpleXMLElement $element
     *
     * @throws InvalidRequestException
     */
    protected function appendArticleData(SimpleXMLElement $element)
    {
        $element[0]->article_data = null;

        foreach ($this->getItems()->all() as $pos => $item) {
            if (!$item instanceof Item) {
                throw new InvalidRequestException('Items must be of instance \\Omnipay\\BillPay\\Item');
            }

            $element[0]->article_data[0]->article[$pos]['articleid'] = $item->getId();
            $element[0]->article_data[0]->article[$pos]['articlequantity'] = $item->getQuantity();
            $element[0]->article_data[0]->article[$pos]['articlename'] = $item->getName();
            $element[0]->article_data[0]->article[$pos]['articledescription'] = $item->getDescription();
            $element[0]->article_data[0]->article[$pos]['articleprice'] = bcmul($item->getPriceNet(), 100, 0);
            $element[0]->article_data[0]->article[$pos]['articlepricegross'] = bcmul($item->getPrice(), 100, 0);
        }
    }

    /**
     * @param SimpleXMLElement $element
     *
     * @throws InvalidRequestException
     */
    protected function appendCustomerDetails(SimpleXMLElement $element)
    {
        $card = $this->getCard();
        $customer = $this->getCustomerDetails();

        $element[0]->customer_details[0]['customerid'] = $customer->getId();
        $element[0]->customer_details[0]['customertype'] = $customer->getType();
        $element[0]->customer_details[0]['salutation'] = null;
        $element[0]->customer_details[0]['title'] = $card->getBillingTitle();
        $element[0]->customer_details[0]['firstName'] = $card->getBillingFirstName();
        $element[0]->customer_details[0]['lastName'] = $card->getBillingLastName();
        $element[0]->customer_details[0]['street'] = $card->getBillingAddress1();
        $element[0]->customer_details[0]['streetNo'] = null;
        $element[0]->customer_details[0]['addressAddition'] = $card->getBillingAddress2();
        $element[0]->customer_details[0]['zip'] = $card->getBillingPostcode();
        $element[0]->customer_details[0]['city'] = $card->getBillingCity();
        $element[0]->customer_details[0]['country'] = $this->getCountryCode($card->getBillingCountry());
        $element[0]->customer_details[0]['email'] = $card->getEmail();
        $element[0]->customer_details[0]['phone'] = $card->getBillingPhone();
        $element[0]->customer_details[0]['cellPhone'] = null;
        $element[0]->customer_details[0]['birthday'] = $card->getBirthday('Ymd');
        $element[0]->customer_details[0]['language'] = $customer->getLanguage();
        $element[0]->customer_details[0]['ip'] = $this->getClientIp();
        $element[0]->customer_details[0]['customerGroup'] = $customer->getGroup();
    }

    /**
     * @param SimpleXMLElement $element
     *
     * @throws InvalidRequestException
     */
    protected function appendShippingDetails(SimpleXMLElement $element)
    {
        $card = $this->getCard();

        $same = 1;

        foreach (['Title', 'FirstName', 'LastName', 'Address1', 'Address2', 'Postcode', 'City', 'Country'] as $check) {
            if ($card->{'getBilling' . $check}() !== $card->{'getShipping' . $check}()) {
                $same = 0;
            }
        }

        if ($same) {
            $element[0]->shipping_details[0]['useBillingAddress'] = 1;
            $element[0]->shipping_details[0]['salutation'] = null;
            $element[0]->shipping_details[0]['title'] = null;
            $element[0]->shipping_details[0]['firstName'] = null;
            $element[0]->shipping_details[0]['lastName'] = null;
            $element[0]->shipping_details[0]['street'] = null;
            $element[0]->shipping_details[0]['streetNo'] =  null;
            $element[0]->shipping_details[0]['addressAddition'] = null;
            $element[0]->shipping_details[0]['zip'] = null;
            $element[0]->shipping_details[0]['city'] = null;
            $element[0]->shipping_details[0]['country'] = null;
            $element[0]->shipping_details[0]['phone'] = null;
            $element[0]->shipping_details[0]['cellPhone'] = null;
        } else {
            $element[0]->shipping_details[0]['useBillingAddress'] = 0;
            $element[0]->shipping_details[0]['salutation'] = null;
            $element[0]->shipping_details[0]['title'] = $card->getShippingTitle();
            $element[0]->shipping_details[0]['firstName'] = $card->getShippingFirstName();
            $element[0]->shipping_details[0]['lastName'] = $card->getShippingLastName();
            $element[0]->shipping_details[0]['street'] = $card->getShippingAddress1();
            $element[0]->shipping_details[0]['streetNo'] = null;
            $element[0]->shipping_details[0]['addressAddition'] = $card->getShippingAddress2();
            $element[0]->shipping_details[0]['zip'] = $card->getShippingPostcode();
            $element[0]->shipping_details[0]['city'] = $card->getShippingCity();
            $element[0]->shipping_details[0]['country'] = $this->getCountryCode($card->getShippingCountry());
            $element[0]->shipping_details[0]['phone'] = $card->getShippingPhone();
            $element[0]->shipping_details[0]['cellPhone'] = null;
        }
    }

    /**
     * @param SimpleXMLElement $response
     *
     * @return ResponseInterface
     */
    protected function createResponse($response)
    {
        return $this->response = new AuthorizeResponse($this, $response);
    }
}

# vim :set ts=4 sw=4 sts=4 et :