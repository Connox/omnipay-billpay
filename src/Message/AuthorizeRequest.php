<?php

namespace Omnipay\BillPay\Message;

use Omnipay\BillPay\Message\RequestData\ArticleDataTrait;
use Omnipay\BillPay\Message\RequestData\BankAccountTrait;
use Omnipay\BillPay\Message\RequestData\CustomerDetailsTrait;
use Omnipay\BillPay\Message\RequestData\DataTrait;
use Omnipay\BillPay\Message\RequestData\RateTrait;
use Omnipay\BillPay\Message\RequestData\ShippingDetailsTrait;
use Omnipay\BillPay\Message\RequestData\TotalTrait;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\ResponseInterface;
use SimpleXMLElement;

/**
 * Message AuthorizeRequest
 *
 * @link      https://techdocs.billpay.de/en/For_developers/XML_API/Preauthorize.html
 *
 * @author    Andreas Lange <andreas.lange@connox.de>
 * @copyright 2016, Connox GmbH
 * @license   MIT
 */
class AuthorizeRequest extends AbstractRequest
{
    use DataTrait;
    use CustomerDetailsTrait;
    use ShippingDetailsTrait;
    use ArticleDataTrait;
    use TotalTrait;
    use RateTrait;
    use BankAccountTrait;

    const PAYMENT_TYPE_INVOICE = 'invoice';
    const PAYMENT_TYPE_DIRECT_DEBIT = 'direct_debit';
    const PAYMENT_TYPE_TRANSACTION_CREDIT = 'transaction_credit';
    const PAYMENT_TYPE_PAY_LATER = 'pay_later';
    const PAYMENT_TYPE_COLLATERAL_PROMISE = 'collateral_promise';

    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @throws InvalidRequestException
     *
     * @return SimpleXMLElement
     */
    public function getData()
    {
        $this->validateData();

        $data = $this->getBaseData();

        $this->appendData($data);
        $this->appendCustomerDetails($data);
        $this->appendShippingDetails($data);
        $this->appendArticleData($data);
        $this->appendTotal($data);
        $this->appendRate($data);
        $this->appendBankAccount($data);

        return $data;
    }

    /**
     * Set the payment method.
     *
     * @param string $value
     *
     * @throws InvalidRequestException
     *
     * @return AbstractRequest Provides a fluent interface
     *
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
     * @param SimpleXMLElement $response
     *
     * @return ResponseInterface
     */
    protected function createResponse($response)
    {
        return $this->response = new AuthorizeResponse($this, $response);
    }

    /**
     * @return string
     */
    protected function getEndpoint()
    {
        return parent::getEndpoint() . '/preauthorize';
    }

    /**
     * @throws InvalidRequestException
     */
    private function validateData()
    {
        $this->validateDataCard();
        $this->validateDataItems();
        $this->validateDataCustomerDetails();
    }

    /**
     * @throws InvalidRequestException
     */
    private function validateDataCard()
    {
        if ($this->getCard() === null) {
            throw new InvalidRequestException('Credit card object required.');
        }
    }

    /**
     * @throws InvalidRequestException
     */
    private function validateDataCustomerDetails()
    {
        if ($this->getCustomerDetails() === null) {
            throw new InvalidRequestException('Customer object required.');
        }
    }

    /**
     * @throws InvalidRequestException
     */
    private function validateDataItems()
    {
        if ($this->getItems() === null || $this->getItems()->count() === 0) {
            throw new InvalidRequestException('Item objects are required.');
        }
    }
}
