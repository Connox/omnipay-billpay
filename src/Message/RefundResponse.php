<?php

namespace Omnipay\BillPay\Message;

use Omnipay\BillPay\Message\ResponseData\BaseDataTrait;
use Omnipay\Common\Message\AbstractResponse;

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
 * @author    Andreas Lange <andreas.lange@quillo.de>
 * @copyright 2016, Quillo GmbH
 * @license   MIT
 */
class RefundResponse extends AbstractResponse
{
    use BaseDataTrait;
}
