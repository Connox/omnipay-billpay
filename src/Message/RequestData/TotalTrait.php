<?php

namespace Omnipay\BillPay\Message\RequestData;

use Omnipay\BillPay\Item;
use Omnipay\BillPay\Message\AuthorizeRequest;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\ItemBag;
use Omnipay\Common\Message\AbstractRequest;
use SimpleXMLElement;

/**
 * Class TotalTrait
 *
 * @package   Omnipay\BillPay
 * @author    Andreas Lange <andreas.lange@quillo.de>
 * @copyright 2016, Quillo GmbH
 * @license   MIT
 */
trait TotalTrait
{
    /**
     * Validates and returns the formated amount.
     *
     * @return string The amount formatted to the correct number of decimal places for the selected currency.
     */
    abstract public function getAmount();

    /**
     * Get the payment currency code.
     *
     * @return string
     */
    abstract public function getCurrency();

    /**
     * A list of items in this order
     *
     * @return ItemBag|null A bag containing items in this order
     */
    abstract public function getItems();

    /**
     * Gets the net rebate amount for the order
     *
     * @return string|null
     */
    public function getRebate()
    {
        return $this->getParameter('rebate');
    }

    /**
     * Gets the gross rebate amount for the order
     *
     * @return string|null
     */
    public function getRebateGross()
    {
        return $this->getParameter('rebateGross');
    }

    /**
     * Gets the shipping method name
     *
     * @return string|null
     */
    public function getShippingName()
    {
        return $this->getParameter('shippingName');
    }

    /**
     * Gets the net amount of shipping cost for the order
     *
     * @return string|null
     */
    public function getShippingPrice()
    {
        return $this->getParameter('shippingPrice');
    }

    /**
     * Gets the gross amount of shipping cost for the order
     *
     * @return string|null
     */
    public function getShippingPriceGross()
    {
        return $this->getParameter('shippingPriceGross');
    }

    /**
     * Get the transaction ID.
     *
     * @return string
     */
    abstract public function getTransactionId();

    /**
     * Sets net rebate amount for the order
     *
     * @param float $rebate net rebate amount
     *
     * @return AuthorizeRequest
     */
    public function setRebate($rebate)
    {
        return $this->setParameter('rebate', $rebate);
    }

    /**
     * Sets gross rebate amount for the order
     *
     * @param string $rebateGross gross rebate amount
     *
     * @return AuthorizeRequest
     */
    public function setRebateGross($rebateGross)
    {
        return $this->setParameter('rebateGross', $rebateGross);
    }

    /**
     * Sets shipping method name (e.g. "Express")
     *
     * @param string $name
     *
     * @return AuthorizeRequest
     */
    public function setShippingName($name)
    {
        return $this->setParameter('shippingName', $name);
    }

    /**
     * Sets net amount of shipping cost for the order
     *
     * @param float $price net amount of shipping cost
     *
     * @return AuthorizeRequest
     */
    public function setShippingPrice($price)
    {
        return $this->setParameter('shippingPrice', $price);
    }

    /**
     * Sets gross amount of shipping cost for the order
     *
     * @param string $priceGross gross amount of shipping cost
     *
     * @return AuthorizeRequest
     */
    public function setShippingPriceGross($priceGross)
    {
        return $this->setParameter('shippingPriceGross', $priceGross);
    }

    /**
     * @param SimpleXMLElement $data
     *
     * @throws InvalidRequestException
     */
    protected function appendTotal(SimpleXMLElement $data)
    {
        if ($this->getItems() === null || $this->getItems()->count() === 0) {
            throw new InvalidRequestException('This request requires items.');
        }

        $totalNet = 0.0;
        $totalGross = 0.0;

        foreach ($this->getItems()->all() as $pos => $item) {
            /** @var Item $item */
            $totalNet = bcadd($totalNet, bcmul($item->getPriceNet(), $item->getQuantity(), 8), 8);
            $totalGross = bcadd($totalGross, bcmul($item->getPrice(), $item->getQuantity(), 8), 8);
        }

        // add shipping
        $totalNet = bcadd($totalNet, $this->getShippingPrice(), 8);
        $totalGross = bcadd($totalGross, $this->getShippingPriceGross(), 8);

        // remove rebates
        $totalNet = bcsub($totalNet, $this->getRebate(), 8);
        $totalGross = bcsub($totalGross, $this->getRebateGross(), 8);

        if (bccomp($totalGross, $this->getAmount(), 8) !== 0) {
            throw new InvalidRequestException(
                sprintf(
                    'Amount (%0.2f) differs from calculated amount (%0.2f) (items + shipping - rebate).',
                    $totalGross,
                    $this->getAmount()
                )
            );
        }

        $data->addChild('total');
        $data->total[0]['shippingname'] = $this->getShippingName();
        $data->total[0]['shippingprice'] = round(bcmul($this->getShippingPrice(), 100, 8));
        $data->total[0]['shippingpricegross'] = round(bcmul($this->getShippingPriceGross(), 100, 8));
        $data->total[0]['rebate'] = round(bcmul($this->getRebate(), 100, 8));
        $data->total[0]['rebategross'] = round(bcmul($this->getRebateGross(), 100, 8));
        $data->total[0]['carttotalprice'] = round(bcmul($totalNet, 100, 8));
        $data->total[0]['carttotalpricegross'] = round(bcmul($totalGross, 100, 8));
        $data->total[0]['currency'] = $this->getCurrency();
        $data->total[0]['reference'] = $this->getTransactionId();
    }

    /**
     * Get a single parameter.
     *
     * @param string $key The parameter key
     *
     * @return mixed
     */
    abstract protected function getParameter($key);

    /**
     * Set a single parameter
     *
     * @param string $key   The parameter key
     * @param mixed  $value The value to set
     *
     * @return AbstractRequest Provides a fluent interface
     */
    abstract protected function setParameter($key, $value);
}
