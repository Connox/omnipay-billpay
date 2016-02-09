<?php

namespace Omnipay\BillPay;

/**
 * Class CustomerTest
 *
 * @package   Omnipay\BillPay
 * @author    Andreas Lange <andreas.lange@quillo.de>
 * @copyright 2016, Quillo GmbH
 * @license   MIT
 */
class CustomerTest extends \PHPUnit_Framework_TestCase
{
    public function testDefaultParameters()
    {
        $class = new Customer();
        self::assertEquals(['type' => 'n', 'group' => 'p'], $class->getParameters());
    }
}

# vim :set ts=4 sw=4 sts=4 et :