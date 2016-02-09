<?php

namespace Omnipay\BillPay;

use Omnipay\BillPay\Message\AuthorizeRequest;
use Omnipay\BillPay\Message\CaptureRequest;
use Omnipay\BillPay\Message\RefundRequest;
use Omnipay\Common\AbstractGateway;

/**
 * Class Gateway
 *
 * @package   Omnipay\BillPay
 * @author    Andreas Lange <andreas.lange@quillo.de>
 * @copyright 2016, Quillo GmbH
 * @license   MIT
 */
class Gateway extends AbstractGateway
{
    /**
     * Create a authorize request.
     *
     * @param array $parameters
     *
     * @return AuthorizeRequest
     */
    public function authorize(array $parameters = [])
    {
        return $this->createRequest(AuthorizeRequest::class, $parameters);
    }

    /**
     * @param array $parameters
     *
     * @return CaptureRequest
     */
    public function capture(array $parameters = [])
    {
        return $this->createRequest(CaptureRequest::class, $parameters);
    }

    /**
     * @return array
     */
    public function getDefaultParameters()
    {
        return [
            'merchantId' => '',
            'portalId' => '',
            'securityKey' => '',
            'testMode' => false,
        ];
    }

    /**
     * @return int Merchant ID
     */
    public function getMerchantId()
    {
        return $this->getParameter('merchantId');
    }

    /**
     * Get gateway display name
     * This can be used by carts to get the display name for each gateway.
     */
    public function getName()
    {
        return 'BillPay';
    }

    /**
     * @return int Portal ID
     */
    public function getPortalId()
    {
        return $this->getParameter('portalId');
    }

    /**
     * @return string MD5 hash of the security key generated for this portal. (generated and delivered by BillPay)
     */
    public function getSecurityKey()
    {
        return $this->getParameter('securityKey');
    }

    /**
     * Create a purchase request (is alias of authorize)
     *
     * @param array $parameters
     *
     * @return AuthorizeRequest
     */
    public function purchase(array $parameters = [])
    {
        return $this->authorize($parameters)->setCaptureRequestNecessary(false);
    }

    /**
     * Create a refund request.
     *
     * @param array $parameters
     *
     * @return RefundRequest
     */
    public function refund(array $parameters = [])
    {
        return $this->createRequest(RefundRequest::class, $parameters);
    }

    /**
     * @param int $value Merchant ID
     *
     * @return Gateway
     */
    public function setMerchantId($value)
    {
        $this->setParameter('merchantId', $value);

        return $this;
    }

    /**
     * @param int $value Portal ID
     *
     * @return Gateway
     */
    public function setPortalId($value)
    {
        $this->setParameter('portalId', $value);

        return $this;
    }

    /**
     * @param string $value MD5 hash of the security key generated for this portal. (generated and delivered by BillPay)
     *
     * @return Gateway
     */
    public function setSecurityKey($value)
    {
        $this->setParameter('securityKey', $value);

        return $this;
    }
}
