<?php

namespace Omnipay\BillPay\Message;

use Omnipay\BillPay\Message\RequestData\InvoiceTrait;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\ResponseInterface;
use SimpleXMLElement;

/**
 * Message InvoiceCreatedRequest
 *
 * @link      https://techdocs.billpay.de/en/For_developers/XML_API/InvoiceCreated.html
 *
 * @author    Andreas Lange <andreas.lange@connox.de>
 * @copyright 2016, Connox GmbH
 * @license   MIT
 */
class InvoiceCreatedRequest extends AbstractRequest
{
    use InvoiceTrait;

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
        $data = $this->getBaseData();

        $this->appendInvoice($data);

        return $data;
    }

    /**
     * @param SimpleXMLElement $response
     *
     * @return ResponseInterface
     */
    protected function createResponse($response)
    {
        return $this->response = new InvoiceCreatedResponse($this, $response);
    }

    /**
     * @return string
     */
    protected function getEndpoint()
    {
        return parent::getEndpoint() . '/invoiceCreated';
    }
}
