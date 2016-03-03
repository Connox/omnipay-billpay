<?php

namespace Omnipay\BillPay\Message;

use Omnipay\BillPay\Message\RequestData\ArticleDataTrait;
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
 * @package   Omnipay\BillPay
 * @author    Andreas Lange <andreas.lange@quillo.de>
 * @copyright 2016, Quillo GmbH
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

    const PAYMENT_TYPE_INVOICE = 'invoice';
    const PAYMENT_TYPE_DIRECT_DEBIT = 'direct_debit';
    const PAYMENT_TYPE_TRANSACTION_CREDIT = 'transaction_credit';
    const PAYMENT_TYPE_PAY_LATER = 'pay_later';
    const PAYMENT_TYPE_COLLATERAL_PROMISE = 'collateral_promise';

    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return SimpleXMLElement
     * @throws InvalidRequestException
     */
    public function getData()
    {
        $data = $this->getBaseData();

        $this->appendData($data);
        $this->appendCustomerDetails($data);
        $this->appendShippingDetails($data);
        $this->appendArticleData($data);
        $this->appendTotal($data);
        $this->appendRate($data);

        return $data;
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
     * @param SimpleXMLElement $response
     *
     * @return ResponseInterface
     */
    protected function createResponse($response)
    {
        return $this->response = new AuthorizeResponse($this, $response);
    }
}
