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
class InvoiceCreatedRequestTest extends TestCase
{
    /** @var InvoiceCreatedRequest */
    private $request;

    public function setUp()
    {
        $client = $this->getHttpClient();
        $request = $this->getHttpRequest();

        $this->request = new InvoiceCreatedRequest($client, $request);
        $this->request->initialize(
            [
                'transactionId' => 'ORDER-12345678',
                'currency' => 'EUR',
                'amount' => '23.95',
                'delayInDays' => 2,
            ]
        );
    }

    public function testInvoiceCreatedGetData()
    {
        self::assertXmlStringEqualsXmlFile(
            __DIR__.'/Xml/InvoiceCreatedRequest.GetData.xml',
            $this->request->getData()->asXML()
        );
    }
}
