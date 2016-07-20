<?php

namespace Omnipay\BillPay;

use PHPUnit_Framework_TestCase;

/**
 * Class CustomerTest
 *
 * @package   Omnipay\BillPay
 * @author    Andreas Lange <andreas.lange@connox.de>
 * @copyright 2016, Connox GmbH
 * @license   MIT
 */
class CustomerTest extends PHPUnit_Framework_TestCase
{
    public function testDefaultParameters()
    {
        $class = new Customer();
        self::assertEquals(['type' => 'n', 'group' => 'p'], $class->getParameters());
    }
}
