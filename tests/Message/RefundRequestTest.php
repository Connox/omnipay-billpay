<?php

namespace Omnipay\BillPay\Message;

use Omnipay\Tests\TestCase;

/**
 * Class InvoiceCreatedRequestTest.
 *
 * @author    Andreas Lange <andreas.lange@quillo.de>
 * @copyright 2016, Quillo GmbH
 * @license   MIT
 */
class RefundCreatedRequestTest extends TestCase
{
    /** @var InvoiceCreatedRequest */
    private $request;

    public function setUp()
    {
        $client = $this->getHttpClient();
        $request = $this->getHttpRequest();

        $this->request = new RefundRequest($client, $request);
        $this->request->initialize(
            [
                'transactionId' => 'ORDER-12345678',
                'currency' => 'EUR',
                'amount' => '23.95',
            ]
        );
    }

    public function testInvoiceCreatedGetData()
    {
        self::assertXmlStringEqualsXmlFile(
            __DIR__.'/Xml/RefundRequest.GetData.xml',
            $this->request->getData()->asXML()
        );
    }
}
