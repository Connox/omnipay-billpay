<?php

namespace Omnipay\BillPay\Message\RequestData;

use Omnipay\BillPay\Item;
use Omnipay\BillPay\Message\AuthorizeRequest;
use Omnipay\Common\Exception\InvalidRequestException;
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
     * Gets the net rebate amount for the order
     *
     * @return string|null
     */
    public function getRebate()
    {
        return method_exists($this, 'getParameter') ? $this->getParameter('rebate') : null;
    }

    /**
     * Gets the gross rebate amount for the order
     *
     * @return string|null
     */
    public function getRebateGross()
    {
        return method_exists($this, 'getParameter') ? $this->getParameter('rebateGross') : null;
    }

    /**
     * Gets the shipping method name
     *
     * @return string|null
     */
    public function getShippingName()
    {
        return method_exists($this, 'getParameter') ? $this->getParameter('shippingName') : null;
    }

    /**
     * Gets the net amount of shipping cost for the order
     *
     * @return string|null
     */
    public function getShippingPrice()
    {
        return method_exists($this, 'getParameter') ? $this->getParameter('shippingPrice') : null;
    }

    /**
     * Gets the gross amount of shipping cost for the order
     *
     * @return string|null
     */
    public function getShippingPriceGross()
    {
        return method_exists($this, 'getParameter') ? $this->getParameter('shippingPriceGross') : null;
    }

    /**
     * Sets net rebate amount for the order
     *
     * @param float $rebate net rebate amount
     *
     * @return AuthorizeRequest
     */
    public function setRebate($rebate)
    {
        return method_exists($this, 'setParameter') ? $this->setParameter('rebate', $rebate) : $this;
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
        return method_exists($this, 'setParameter') ? $this->setParameter('rebateGross', $rebateGross) : $this;
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
        return method_exists($this, 'setParameter') ? $this->setParameter('shippingName', $name) : $this;
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
        return method_exists($this, 'setParameter') ? $this->setParameter('shippingPrice', $price) : $this;
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
        return method_exists($this, 'setParameter') ? $this->setParameter('shippingPriceGross', $priceGross) : $this;
    }

    /**
     * @param SimpleXMLElement $data
     *
     * @throws InvalidRequestException
     */
    protected function appendTotal(SimpleXMLElement $data)
    {
        /** @var AbstractRequest|TotalTrait $this */

        if (!$this instanceof AbstractRequest) {
            throw new InvalidRequestException('Trait can only be used inside instance of ' . AbstractRequest::class);
        }

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
}
