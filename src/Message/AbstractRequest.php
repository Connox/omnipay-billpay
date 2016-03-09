<?php

namespace Omnipay\BillPay\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\ResponseInterface;
use SimpleXMLElement;

/**
 * BillPay Abstract Request
 *
 * @link      https://techdocs.billpay.de/en/For_developers/Introduction.html
 *
 * @author    Andreas Lange <andreas.lange@quillo.de>
 * @copyright 2016, Quillo GmbH
 * @license   MIT
 */
abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    const API_VERSION = '1.5.10';

    protected $liveEndpoint = 'https://api.billpay.de/xml';
    protected $testEndpoint = 'https://test-api.billpay.de/xml/offline';

    /**
     * @var string
     */
    protected $rawLastHttpRequest;

    /**
     * @var string
     */
    protected $rawLastHttpResponse;

    /**
     * @param string $country
     *
     * @return string|null ISO-3166-1 Alpha3
     */
    public function getCountryCode($country)
    {
        $countries = [
            'germany' => 'DEU',
            'deu' => 'DEU',
            'de' => 'DEU',
            'austria' => 'AUT',
            'aut' => 'AUT',
            'at' => 'AUT',
            'switzerland' => 'CHE',
            'swiss' => 'CHE',
            'che' => 'CHE',
            'ch' => 'CHE',
            'netherlands' => 'NLD',
            'the netherlands' => 'NLD',
            'nld' => 'NLD',
            'nl' => 'NLD',
        ];

        return array_key_exists(strtolower($country), $countries) ? $countries[strtolower($country)] : null;
    }

    /**
     * @return int Merchant ID
     */
    public function getMerchantId()
    {
        return $this->getParameter('merchantId');
    }

    /**
     * @return int Portal ID
     */
    public function getPortalId()
    {
        return $this->getParameter('portalId');
    }

    /**
     * Gets the raw http request of the last request including header lines.
     *
     * @return string
     */
    public function getRawLastHttpRequest()
    {
        return $this->rawLastHttpRequest;
    }

    /**
     * Gets the raw http response of the last request including header lines.
     *
     * @return string
     */
    public function getRawLastHttpResponse()
    {
        return $this->rawLastHttpResponse;
    }

    /**
     * @return string MD5 hash of the security key generated for this portal. (generated and delivered by BillPay)
     */
    public function getSecurityKey()
    {
        return $this->getParameter('securityKey');
    }

    /**
     * Send the request with specified data
     *
     * @param SimpleXMLElement $data The data to send
     *
     * @throws InvalidRequestException
     *
     * @return ResponseInterface
     */
    public function sendData($data)
    {
        if (!$data instanceof SimpleXMLElement) {
            throw new InvalidRequestException('Data must be XML.');
        }

        $httpRequest = $this->httpClient->post($this->getEndpoint(), null, (string)$data->asXML());
        $this->rawLastHttpRequest = (string)$httpRequest;

        $httpResponse = $httpRequest->send();
        $this->rawLastHttpResponse = $httpResponse->getMessage();

        return $this->createResponse($httpResponse->xml());
    }

    /**
     * @param int $value Merchant ID
     *
     * @return AbstractRequest
     */
    public function setMerchantId($value)
    {
        return $this->setParameter('merchantId', $value);
    }

    /**
     * @param int $value Portal ID
     *
     * @return AbstractRequest
     */
    public function setPortalId($value)
    {
        return $this->setParameter('portalId', $value);
    }

    /**
     * @param string $value MD5 hash of the security key generated for this portal. (generated and delivered by BillPay)
     *
     * @return AbstractRequest
     */
    public function setSecurityKey($value)
    {
        return $this->setParameter('securityKey', $value);
    }

    /**
     * @param SimpleXMLElement $response
     *
     * @return ResponseInterface
     */
    abstract protected function createResponse($response);

    /**
     * @return SimpleXMLElement
     */
    protected function getBaseData()
    {
        $data = new SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><data/>');
        $data['api_version'] = self::API_VERSION;
        $data[0]->default_params['mid'] = $this->getMerchantId();
        $data[0]->default_params['pid'] = $this->getPortalId();
        $data[0]->default_params['bpsecure'] = md5($this->getSecurityKey());

        return $data;
    }

    /**
     * @return string
     */
    protected function getEndpoint()
    {
        return $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
    }
}
