<?php

namespace Omnipay\BillPay\Message;

/**
 * Class RefundResponse
 *
 * Example xml:
 * <code>
 * <?xml version="1.0" encoding="UTF-8" ?>
 * <data api_version="1.5.10" customer_message="" error_code="0" merchant_message="">
 * </data>
 * </code>
 *
 * @package   Omnipay\BillPay
 *
 * @author    Andreas Lange <andreas.lange@quillo.de>
 * @copyright 2016, Quillo GmbH
 * @license   MIT
 */
class RefundResponse extends Response
{
    /**
     * Is the response successful?
     *
     * @return bool
     */
    public function isSuccessful()
    {
        return parent::isSuccessful() && count($this->data->children()) === 0;
    }
}
