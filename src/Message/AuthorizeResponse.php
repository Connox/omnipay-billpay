<?php

namespace Omnipay\BillPay\Message;

/**
 * Class AuthorizeResponse
 *
 * Example xml:
 * <code>
 * <?xml version="1.0" encoding="UTF-8"?>
 * <data bptid="66ffe4d2-dc04-436d-8def-d057eb024154" customer_message="" error_code="0" merchant_message="" status="APPROVED">
 *   <corrected_address street="Musterstraße" streetNo="23" zip="12345" city="Musterstadt" country="DEU" />
 * </data>
 * </code>
 *
 * @package   Omnipay\BillPay
 *
 * @author    Andreas Lange <andreas.lange@quillo.de>
 * @copyright 2016, Quillo GmbH
 * @license   MIT
 */
class AuthorizeResponse extends Response
{
}

# vim :set ts=4 sw=4 sts=4 et :