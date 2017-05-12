<?php

namespace Omnipay\BillPay;

use Omnipay\Common\Helper;
use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * Class Company.
 */
class Company
{
    /**
     * @var ParameterBag
     */
    protected $parameters;

    /**
     * Create a new item with the specified parameters.
     *
     * @param array|null $parameters An array of parameters to set on the new object
     */
    public function __construct($parameters = null)
    {
        $this->initialize($parameters);
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters->all();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->getParameter('name');
    }

    /**
     * @param string $group
     *
     * @return Company
     */
    public function setName($name)
    {
        $this->setParameter('name', $name);

        return $this;
    }

    /**
     * @return string
     */
    public function getLegalForm()
    {
        return $this->getParameter('legalForm');
    }

    /**
     * @param string $legalForm
     *
     * @return Company
     */
    public function setLegalForm($legalForm)
    {
        $this->setParameter('legalForm', $legalForm);

        return $this;
    }

    /**
     * @return string
     */
    public function getRegisterNumber()
    {
        return $this->getParameter('registerNumber');
    }

    /**
     * @param string $registerNumber
     *
     * @return Company
     */
    public function setRegisterNumber($registerNumber)
    {
        $this->setParameter('registerNumber', $registerNumber);

        return $this;
    }

    /**
     * @return string
     */
    public function getHolderName()
    {
        return $this->getParameter('holderName');
    }

    /**
     * @param string $holderName
     *
     * @return Company
     */
    public function setHolderName($holderName)
    {
        $this->setParameter('holderName', $holderName);

        return $this;
    }

    /**
     * @return string
     */
    public function getTaxNumber()
    {
        return $this->getParameter('taxNumber');
    }

    /**
     * @param string $taxNumber
     *
     * @return Company
     */
    public function setTaxNumber($taxNumber)
    {
        $this->setParameter('taxNumber', $taxNumber);

        return $this;
    }

    /**
     * Initialize this item with the specified parameters.
     *
     * @param array|null $parameters An array of parameters to set on this object
     *
     * @return Customer
     */
    public function initialize($parameters = null)
    {
        $this->parameters = new ParameterBag();

        if ($parameters !== null) {
            Helper::initialize($this, $parameters);
        }

        return $this;
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    protected function getParameter($key)
    {
        return $this->parameters->get($key);
    }

    /**
     * @param string $key
     * @param mixed  $value
     *
     * @return Customer
     */
    protected function setParameter($key, $value)
    {
        $this->parameters->set($key, $value);

        return $this;
    }
}
