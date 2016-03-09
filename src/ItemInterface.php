<?php

namespace Omnipay\BillPay;

/**
 * Cart Item interface BillPay extension
 */
interface ItemInterface extends \Omnipay\Common\ItemInterface
{
    /**
     * ID of the item
     *
     * @return string
     */
    public function getId();

    /**
     * Price of the item (net amount, excluding taxes)
     *
     * @return string
     */
    public function getPriceNet();
}
