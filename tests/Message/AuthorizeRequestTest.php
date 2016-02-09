<?php

namespace Omnipay\BillPay\Message;

use Omnipay\BillPay\Customer;
use Omnipay\BillPay\Item;
use Omnipay\Common\CreditCard;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\ItemBag;
use Omnipay\Tests\TestCase;

/**
 * Class AuthorizeRequestTest
 *
 * @package   Omnipay\BillPay
 * @author    Andreas Lange <andreas.lange@quillo.de>
 * @copyright 2016, Quillo GmbH
 * @license   MIT
 */
class AuthorizeRequestTest extends TestCase
{
    /** @var AuthorizeRequest */
    private $request;

    public function setUp()
    {
        $client = $this->getHttpClient();
        $request = $this->getHttpRequest();

        $this->request = new AuthorizeRequest($client, $request);
        $this->request->setPaymentMethod(AuthorizeRequest::PAYMENT_TYPE_INVOICE);
        $this->request->setExpectedDaysTillShipping(2);
        $this->request->setCard(new CreditCard());
        $this->request->setCustomerDetails(new Customer());
        $this->request->setItems(new ItemBag([
            new Item([
                'id' => '1',
                'name' => 'IT-12345',
                'description' => 'Article 12345 - white',
                'quantity' => 1,
                'price' => '5.00',
                'priceNet' => '4.2017'
            ]),
            new Item([
                'id' => '2',
                'name' => 'IT-67890',
                'description' => 'Item 67890',
                'quantity' => 1,
                'price' => '5.00',
                'priceNet' => '4.2017'
            ]),
        ]));
    }

    public function testPaymentMethodNotSet()
    {
        self::setExpectedException(InvalidRequestException::class, 'This request requires a payment method.');
        $this->request->setPaymentMethod(null);
        $this->request->getData();
    }

    public function testPaymentMethodInvalid()
    {
        self::setExpectedException(InvalidRequestException::class, 'Unknown payment method specified \'bananas\' specified.');
        $this->request->setPaymentMethod('bananas');
        $this->request->getData();
    }

    public function testCardNotExist()
    {
        self::setExpectedException(InvalidRequestException::class,
            'This request requires a credit card object and a customer object for address details.');
        $this->request->setCard(null);
        $this->request->getData();
    }

    public function testCustomerNotExist()
    {
        self::setExpectedException(InvalidRequestException::class,
            'This request requires a customer object for additional details not covered by card.');
        $this->request->setCustomerDetails(null);
        $this->request->getData();
    }

    public function testItemsNotExist()
    {
        self::setExpectedException(InvalidRequestException::class, 'This request requires items.');
        $this->request->setItems(null);
        $this->request->getData();
    }

    public function testItemsIncorrectType()
    {
        self::setExpectedException(InvalidRequestException::class, 'Items must be of instance \Omnipay\BillPay\Item');
        $this->request->setItems(new ItemBag([
            new \Omnipay\Common\Item([
                'id' => '1',
                'name' => 'IT-12345',
                'description' => 'Article 12345 - white',
                'quantity' => 1,
                'price' => '5.00',
                'priceNet' => '4.2017'
            ])
        ]));
        $this->request->getData();
    }

    public function testGetData()
    {
        self::assertXmlStringEqualsXmlFile(__DIR__ . '/Xml/AuthorizeRequest.xml', $this->request->getData()->asXML());
    }
}

# vim :set ts=4 sw=4 sts=4 et :