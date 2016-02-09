<?php

namespace Omnipay\BillPay;

use Guzzle\Http\Client;
use Omnipay\BillPay\Message\DummyRequest;
use Omnipay\Common\Exception\InvalidRequestException;
use Symfony\Component\HttpFoundation\Request;

require_once __DIR__ . '/Mock/DummyRequest.php';

class AbstractRequestTest extends \PHPUnit_Framework_TestCase
{
    public function testDefectXmlSend()
    {
        $mock = new DummyRequest(new Client(), new Request());

        self::setExpectedException(InvalidRequestException::class);
        $mock->send();
    }
}

# vim :set ts=4 sw=4 sts=4 et :