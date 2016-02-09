<?php

namespace Omnipay\BillPay;

/**
 * Cart Item interface BillPay extension
 *
 * @package Omnipay\BillPay
 */
interface ItemInterface extends \Omnipay\Common\ItemInterface
{
    /**
     * Price of the item (net amount, excluding taxes)
     *
     * @return string
     */
    public function getPriceNet();

    /**
     * ID of the item
     *
     * @return string
     */
    public function getId();
}

# vim :set ts=4 sw=4 sts=4 et :