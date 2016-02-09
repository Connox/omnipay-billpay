<?php

namespace Omnipay\BillPay;

/**
 * Class Item
 *
 * @package   Omnipay\BillPay
 * @author    Andreas Lange <andreas.lange@quillo.de>
 * @copyright 2016, Quillo GmbH
 * @license   MIT
 */
class Item extends \Omnipay\Common\Item implements ItemInterface
{
    /**
     * ID of the item
     *
     * @return string
     */
    public function getId()
    {
        return $this->getParameter('id');
    }

    /**
     * Price of the item (net amount, excluding taxes)
     *
     * @return string
     */
    public function getPriceNet()
    {
        return $this->getParameter('priceNet');
    }

    /**
     * Sets the ID of the item
     *
     * @param string $value
     *
     * @return Item
     */
    public function setId($value)
    {
        return $this->setParameter('id', $value);
    }

    /**
     * Sets the price of the item (net amount, excluding taxes)
     *
     * @param string $value
     *
     * @return string
     */
    public function setPriceNet($value)
    {
        return $this->setParameter('priceNet', $value);
    }
}
