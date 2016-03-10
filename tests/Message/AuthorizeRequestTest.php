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
        $this->request->initialize(
            [
                'transactionId' => 'ORDER-12345678',
                'paymentMethod' => AuthorizeRequest::PAYMENT_TYPE_PAY_LATER,
                'expectedDaysTillShipping' => 2,
                'card' => new CreditCard(),
                'customerDetails' => new Customer(),
                'currency' => 'EUR',
                'amount' => '23.95'
            ]
        );

        $this->request->setShippingName('Express')->setShippingPrice(4.1596)->setShippingPriceGross(4.95);
        $this->request->setRebate(0.84)->setRebateGross(1.0);

        $this->request->setAccountHolder('John Doe');
        $this->request->setAccountNumber('DE12500105170648489890');
        $this->request->setSortCode('');

        $this->request->setRateCount(12);
        $this->request->setRateTerm(24);
        $this->request->setRateTotalAmount('34.56');

        $this->request->setItems(
            new ItemBag(
                [
                    new Item(
                        [
                            'id' => '1',
                            'name' => 'IT-12345',
                            'description' => 'Article 12345 - white',
                            'quantity' => 1,
                            'price' => '5.00',
                            'priceNet' => '4.2017'
                        ]
                    ),
                    new Item(
                        [
                            'id' => '2',
                            'name' => 'IT-67890',
                            'description' => 'Item 67890',
                            'quantity' => 3,
                            'price' => '5.00',
                            'priceNet' => '4.2017'
                        ]
                    ),
                ]
            )
        );

    }

    public function testRate()
    {
        self::assertEquals('12', $this->request->getRateCount());
        self::assertEquals('24', $this->request->getRateTerm());
        self::assertEquals('34.56', $this->request->getRateTotalAmount());
    }

    public function testBankAccount()
    {
        self::assertEquals('John Doe', $this->request->getAccountHolder());
        self::assertEquals('DE12500105170648489890', $this->request->getAccountNumber());
        self::assertEquals('', $this->request->getSortCode());
    }

    public function testAmountDifference()
    {
        self::setExpectedException(
            InvalidRequestException::class,
            'Amount (23.95) differs from calculated amount (0.00) (items + shipping - rebate).'
        );
        $this->request->setAmount(0.0);
        $this->request->getData();
    }

    public function testCardNotExist()
    {
        self::setExpectedException(
            InvalidRequestException::class,
            'Credit card object required.'
        );
        $this->request->setCard(null);
        $this->request->getData();
    }

    public function testCustomerNotExist()
    {
        self::setExpectedException(
            InvalidRequestException::class,
            'Customer object required for additional details not covered by card.'
        );
        $this->request->setCustomerDetails(null);
        $this->request->getData();
    }

    public function testDifferingAddresses()
    {
        $card = new CreditCard(
            [
                'firstName' => 'TEST2',
                'billingFirstName' => 'TEST1'
            ]
        );

        self::assertXmlStringEqualsXmlFile(
            __DIR__ . '/Xml/AuthorizeRequest.DifferingAddresses.xml',
            $this->request->setCard($card)->getData()->asXML()
        );
    }

    public function testGetData()
    {
        self::assertXmlStringEqualsXmlFile(
            __DIR__ . '/Xml/AuthorizeRequest.GetData.xml',
            $this->request->getData()->asXML()
        );
    }

    public function testItemsIncorrectType()
    {
        self::setExpectedException(
            InvalidRequestException::class,
            'All items must be of instance of Omnipay\BillPay\Item'
        );
        $this->request->setItems(
            new ItemBag(
                [
                    new \Omnipay\Common\Item(
                        [
                            'id' => '1',
                            'name' => 'IT-12345',
                            'description' => 'Article 12345 - white',
                            'quantity' => 1,
                            'price' => '5.00',
                            'priceNet' => '4.2017'
                        ]
                    )
                ]
            )
        );
        $this->request->getData();
    }

    public function testItemsNotExist()
    {
        self::setExpectedException(InvalidRequestException::class, 'This request requires items.');
        $this->request->setItems(null);
        $this->request->getData();
    }

    public function testPaymentMethodInvalid()
    {
        self::setExpectedException(
            InvalidRequestException::class,
            'Unknown payment method specified \'bananas\' specified.'
        );
        $this->request->setPaymentMethod('bananas');
        $this->request->getData();
    }

    public function testPaymentMethodNotSet()
    {
        self::setExpectedException(InvalidRequestException::class, 'This request requires a payment method.');
        $this->request->setPaymentMethod(null);
        $this->request->getData();
    }
}
