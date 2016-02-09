<?php

namespace Omnipay\BillPay\Message;

class DummyRequest extends AbstractRequest
{
    public $data;

    public function getData()
    {
        return $this->data;
    }

    protected function createResponse($response)
    {
        return null;
    }
}

# vim :set ts=4 sw=4 sts=4 et :