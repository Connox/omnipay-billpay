<?php

namespace Omnipay\BillPay;

use Mockery;
use Omnipay\BillPay\Message\AbstractRequest;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\MessageInterface;
use PHPUnit_Framework_TestCase;

/**
 * Class AbstractRequestTest
 *
 * @package   Omnipay\BillPay
 * @author    Andreas Lange <andreas.lange@connox.de>
 * @copyright 2016, Connox GmbH
 * @license   MIT
 */
class AbstractRequestTest extends PHPUnit_Framework_TestCase
{
    public function testDefectXmlSend()
    {
        $mock = Mockery::mock(AbstractRequest::class, MessageInterface::class)->makePartial();
        $mock->shouldReceive('getData')->andReturn(null);

        self::setExpectedException(InvalidRequestException::class);

        /** @var AbstractRequest $mock */
        $mock->send();
    }
}
