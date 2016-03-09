<?php

namespace Omnipay\BillPay;

use Omnipay\BillPay\Message\AuthorizeRequest;
use Omnipay\BillPay\Message\AuthorizeResponse;
use Omnipay\BillPay\Message\CaptureResponse;
use Omnipay\Common\CreditCard;
use Omnipay\Common\ItemBag;
use Omnipay\Tests\GatewayTestCase;

/**
 * Class GatewayTest
 *
 * @package   Omnipay\BillPay
 * @author    Andreas Lange <andreas.lange@quillo.de>
 * @copyright 2016, Quillo GmbH
 * @license   MIT
 */
class GatewayTest extends GatewayTestCase
{
    /** @var Gateway */
    public $gateway;

    /** @var array */
    public $options;

    /** @var ItemBag */
    public $items;

    /** @var CreditCard */
    public $card;

    /** @var Customer */
    public $customer;

    public function setUp()
    {
        parent::setUp();

        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());

        $this->options = [
            'merchantId' => '4441',
            'portalId' => '6021',
            'securityKey' => '25d55ad283aa400af464c76d713c07ad',
            'currency' => 'EUR',
            'amount' => '10.00',
            'transactionId' => 'ORDER-12345678'
        ];

        $this->items = new ItemBag(
            [
                new Item(
                    [
                        'id' => '1',
                        'name' => 'IT-12345',
                        'description' => 'Article 12345 - white',
                        'quantity' => 1,
                        'price' => '5.00',
                        'priceNet' => '4.201680672268908'
                    ]
                ),
                new Item(
                    [
                        'id' => '2',
                        'name' => 'IT-67890',
                        'description' => 'Item 67890',
                        'quantity' => 1,
                        'price' => '5.00',
                        'priceNet' => '4.201680672268908'
                    ]
                ),
            ]
        );

        $this->card = new CreditCard(
            [
                'title' => '',
                'firstName' => 'Herbert 8549403905',
                'lastName' => 'BillPay 8549403905',
                'address1' => 'Teststrasse 8549403905 123',
                'address2' => '',
                'city' => 'Teststadt 8549403905',
                'postcode' => '12345',
                'country' => 'DEU',
                'phone' => '0302333459',
                'fax' => '',
                'email' => 'testing@billpay.de',
                'birthday' => '1985-09-11',
                'gender' => '',
            ]
        );

        $this->customer = new Customer(
            [
                'id' => '123456',
                'type' => Customer::TYPE_EXISTING,
                'group' => Customer::GROUP_PRIVATE,
                'language' => Customer::LANGUAGE_GERMAN,
            ]
        );
    }

    public function testAuthorizeCorrectedAddress()
    {
        $this->setMockHttpResponse('Preauthorize.CorrectedAddress.txt');

        /** @var AuthorizeResponse $response */
        $response = $this->gateway->authorize($this->options)
            ->setCustomerDetails($this->customer)
            ->setItems($this->items)
            ->setCard($this->card)
            ->setPaymentMethod(AuthorizeRequest::PAYMENT_TYPE_INVOICE)
            ->send();

        self::assertTrue($response->hasCorrectedAddress());
        self::assertEquals(
            [
                'street' => 'Teststrasse 8549403905',
                'streetNo' => '123',
                'zip' => '12345',
                'city' => 'Teststadt 8549403905',
                'country' => 'DEU'
            ],
            $response->getCorrectedAddress()
        );
    }

    public function testAuthorizeEmptyResponse()
    {
        $this->setMockHttpResponse('Empty.txt');

        $response = $this->gateway->authorize($this->options)
            ->setCustomerDetails($this->customer)
            ->setItems($this->items)
            ->setCard($this->card)
            ->setPaymentMethod(AuthorizeRequest::PAYMENT_TYPE_INVOICE)
            ->send();

        self::assertFalse($response->isSuccessful());
    }

    public function testAuthorizeFailed()
    {
        $this->setMockHttpResponse('Failed.txt');

        /** @var AuthorizeResponse $response */
        $response = $this->gateway->authorize($this->options)
            ->setCustomerDetails($this->customer)
            ->setItems($this->items)
            ->setCard($this->card)
            ->setPaymentMethod(AuthorizeRequest::PAYMENT_TYPE_INVOICE)
            ->send();

        self::assertFalse($response->isSuccessful());
        self::assertEquals('1aa2fb2d-2b78-4393-bf06-be0012dda337', $response->getTransactionReference());
        self::assertEquals('CustomerMessage', $response->getMessage());
        self::assertEquals('12345', $response->getCode());
        self::assertNull($response->getCorrectedAddress());
    }

    public function testAuthorizeInvoiceBankAccount()
    {
        $this->setMockHttpResponse('Preauthorize.InvoiceBankAccount.txt');

        /** @var AuthorizeResponse $response */
        $response = $this->gateway->authorize($this->options)
            ->setCustomerDetails($this->customer)
            ->setItems($this->items)
            ->setCard($this->card)
            ->setPaymentMethod(AuthorizeRequest::PAYMENT_TYPE_INVOICE)
            ->send();

        self::assertTrue($response->hasInvoiceBankAccount());
        self::assertEquals(
            [
                'account_holder' => 'BillPay GmbH',
                'account_number' => 'DE07312312312312312',
                'bank_code' => 'BELADEBEXXX',
                'bank_name' => 'Sparkasse Berlin',
                'invoice_reference' => 'BP555666777/9999',
            ],
            $response->getInvoiceBankAccount()
        );
    }

    public function testAuthorizePaymentPlan()
    {
        $this->setMockHttpResponse('Preauthorize.PaymentPlan.txt');

        /** @var AuthorizeResponse $response */
        $response = $this->gateway->authorize($this->options)
            ->setCustomerDetails($this->customer)
            ->setItems($this->items)
            ->setCard($this->card)
            ->setPaymentMethod(AuthorizeRequest::PAYMENT_TYPE_INVOICE)
            ->send();

        self::assertTrue($response->hasPaymentPlan());
        self::assertEquals(
            [
                'num_inst' => '24',
                'duration' => '25',
                'fee_percent' => '18.00',
                'fee_total' => '125.82',
                'pre_payment' => '0.00',
                'total_amount' => '863.82',
                'eff_anual' => '0.18',
                'nominal' => '0.17',
                'instl' => [
                    [
                        'date' => '2016-03-09',
                        'type' => 'immediate',
                        'amount' => '101.91',
                    ],
                    [
                        'date' => '2016-04-09',
                        'type' => 'first',
                        'amount' => '29.24',
                    ],
                    [
                        'date' => '2016-05-09',
                        'type' => 'date',
                        'amount' => '29.12',
                    ],
                    [
                        'date' => '2016-06-09',
                        'type' => 'date',
                        'amount' => '29.12',
                    ],
                    [
                        'date' => '2016-07-09',
                        'type' => 'date',
                        'amount' => '29.12',
                    ],
                    [
                        'date' => '2016-08-09',
                        'type' => 'date',
                        'amount' => '29.12',
                    ],
                    [
                        'date' => '2016-09-09',
                        'type' => 'date',
                        'amount' => '29.12',
                    ],
                    [
                        'date' => '2016-10-09',
                        'type' => 'date',
                        'amount' => '29.12',
                    ],
                    [
                        'date' => '2016-11-09',
                        'type' => 'date',
                        'amount' => '29.12',
                    ],
                    [
                        'date' => '2016-12-09',
                        'type' => 'date',
                        'amount' => '29.12',
                    ],
                    [
                        'date' => '2017-01-09',
                        'type' => 'date',
                        'amount' => '29.12',
                    ],
                    [
                        'date' => '2017-02-09',
                        'type' => 'date',
                        'amount' => '29.12',
                    ],
                    [
                        'date' => '2017-03-09',
                        'type' => 'date',
                        'amount' => '29.12',
                    ],
                    [
                        'date' => '2017-04-09',
                        'type' => 'date',
                        'amount' => '29.12',
                    ],
                    [
                        'date' => '2017-05-09',
                        'type' => 'date',
                        'amount' => '29.12',
                    ],
                    [
                        'date' => '2017-06-09',
                        'type' => 'date',
                        'amount' => '29.12',
                    ],
                    [
                        'date' => '2017-07-09',
                        'type' => 'date',
                        'amount' => '29.12',
                    ],
                    [
                        'date' => '2017-08-09',
                        'type' => 'date',
                        'amount' => '29.12',
                    ],
                    [
                        'date' => '2017-09-09',
                        'type' => 'date',
                        'amount' => '29.12',
                    ],
                    [
                        'date' => '2017-10-09',
                        'type' => 'date',
                        'amount' => '29.12',
                    ],
                    [
                        'date' => '2017-11-09',
                        'type' => 'date',
                        'amount' => '29.12',
                    ],
                    [
                        'date' => '2017-12-09',
                        'type' => 'date',
                        'amount' => '29.12',
                    ],
                    [
                        'date' => '2018-01-09',
                        'type' => 'date',
                        'amount' => '29.12',
                    ],
                    [
                        'date' => '2018-02-09',
                        'type' => 'date',
                        'amount' => '29.12',
                    ],
                    [
                        'date' => '2018-03-09',
                        'type' => 'date',
                        'amount' => '29.12',
                    ],
                    [
                        'date' => '2018-04-09',
                        'type' => 'fee',
                        'amount' => '62.91',
                    ],
                ],
            ],
            $response->getPaymentPlan()
        );
    }

    public function testAuthorizeSuccess()
    {
        $this->setMockHttpResponse('Preauthorize.txt');

        /** @var AuthorizeResponse $response */
        $response = $this->gateway->authorize($this->options)
            ->setCustomerDetails($this->customer)
            ->setItems($this->items)
            ->setCard($this->card)
            ->setPaymentMethod(AuthorizeRequest::PAYMENT_TYPE_INVOICE)
            ->send();

        self::assertTrue($response->isSuccessful());
        self::assertEquals('1aa2fb2d-2b78-4393-bf06-be0012dda337', $response->getTransactionReference());
        self::assertNull($response->getMessage());
        self::assertNull($response->getCode());

        self::assertEquals('APPROVED', $response->getStatus());

        self::assertFalse($response->hasCorrectedAddress());
        self::assertNull($response->getCorrectedAddress());
        self::assertFalse($response->hasInvoiceBankAccount());
        self::assertNull($response->getInvoiceBankAccount());
        self::assertFalse($response->hasPaymentPlan());
        self::assertNull($response->getPaymentPlan());
    }

    public function testCaptureFailure()
    {
        $this->setMockHttpResponse('Failed.txt');

        /** @var CaptureResponse $response */
        $response = $this->gateway->capture($this->options)->send();

        self::assertFalse($response->isSuccessful());
        self::assertNull($response->getInvoiceBankAccount());
    }

    public function testCaptureSuccess()
    {
        $this->setMockHttpResponse('Capture.txt');

        /** @var CaptureResponse $response */
        $response = $this->gateway->capture($this->options)->send();

        self::assertTrue($response->isSuccessful());
        self::assertNull($response->getMessage());
        self::assertEquals(
            [
                'account_holder' => 'BillPay GmbH',
                'account_number' => 'DE07312312312312312',
                'bank_code' => 'BELADEBEXXX',
                'bank_name' => 'Sparkasse Berlin',
                'invoice_reference' => 'BP555666777/9999',
            ],
            $response->getInvoiceBankAccount()
        );
    }

    public function testInvoiceCreatedSuccess()
    {
        $this->setMockHttpResponse('InvoiceCreated.txt');

        $response = $this->gateway->invoiceCreated($this->options)->send();

        self::assertTrue($response->isSuccessful());
        self::assertNull($response->getMessage());
    }

    public function testRawData()
    {
        $this->setMockHttpResponse('Preauthorize.txt');

        $request = $this->gateway->authorize($this->options);
        $request->setCustomerDetails($this->customer);
        $request->setItems($this->items);
        $request->setCard($this->card);
        $request->setPaymentMethod(AuthorizeRequest::PAYMENT_TYPE_INVOICE);

        $request->send();

        $rawRequest = $request->getRawLastHttpRequest();
        $rawResponse = $request->getRawLastHttpResponse();

        self::assertContains('Host: api.billpay.de', $rawRequest);
        self::assertContains('mid="4441" pid="6021" bpsecure="550e1bafe077ff0b0b67f4e32f29d751"', $rawRequest);
        self::assertContains('<?xml version="1.0" encoding="UTF-8" standalone="no"?>', $rawResponse);
        self::assertContains('Content-Type: application/xml; charset=utf-8', $rawResponse);
        self::assertContains('bptid="1aa2fb2d-2b78-4393-bf06-be0012dda337"', $rawResponse);
    }

    public function testRefundSuccess()
    {
        $this->setMockHttpResponse('Cancel.txt');

        $response = $this->gateway->refund($this->options)->send();

        self::assertTrue($response->isSuccessful());
        self::assertNull($response->getMessage());
    }
}
