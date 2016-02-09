<?php

namespace Omnipay\BillPay\Message;

use Omnipay\Common\Message\ResponseInterface;
use SimpleXMLElement;

/**
 * Message CaptureRequest
 *
 * @link      https://techdocs.billpay.de/en/For_developers/XML_API/Capture.html
 * @package   Omnipay\BillPay
 * @author    Andreas Lange <andreas.lange@quillo.de>
 * @copyright 2016, Quillo GmbH
 * @license   MIT
 */
class CaptureRequest extends AbstractRequest
{
    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return SimpleXMLElement
     */
    public function getData()
    {
        return $this->getBaseData();
    }

    /**
     * @param SimpleXMLElement $response
     *
     * @return ResponseInterface
     */
    protected function createResponse($response)
    {
        return $this->response = new CaptureResponse($this, $response);
    }
}
