<?php

namespace Omnipay\BillPay;

use Omnipay\Common\Helper;
use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * Class Customer
 *
 * @author    Andreas Lange <andreas.lange@quillo.de>
 * @copyright 2016, Quillo GmbH
 * @license   MIT
 */
class Customer
{
    const TYPE_NEW = 'n';
    const TYPE_GUEST = 'g';
    const TYPE_EXISTING = 'e';

    const GROUP_PRIVATE = 'p';
    const GROUP_BUSINESS = 'b';

    const LANGUAGE_GERMAN = 'de';
    const LANGUAGE_FRENCH = 'fr';
    const LANGUAGE_ITALIAN = 'it';
    const LANGUAGE_DUTCH = 'nl';

    /**
     * @var ParameterBag
     */
    protected $parameters;

    /**
     * Create a new item with the specified parameters
     *
     * @param array|null $parameters An array of parameters to set on the new object
     */
    public function __construct($parameters = null)
    {
        $this->initialize($parameters);
    }

    /**
     * @return string
     */
    public function getGroup()
    {
        return $this->getParameter('group');
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->getParameter('id');
    }

    /**
     * @return string
     */
    public function getLanguage()
    {
        return $this->getParameter('language');
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
    public function getType()
    {
        return $this->getParameter('type');
    }

    /**
     * Initialize this item with the specified parameters
     *
     * @param array|null $parameters An array of parameters to set on this object
     *
     * @return Customer
     */
    public function initialize($parameters = null)
    {
        $this->parameters = new ParameterBag;
        $this->setType(self::TYPE_NEW);
        $this->setGroup(self::GROUP_PRIVATE);

        if ($parameters !== null) {
            Helper::initialize($this, $parameters);
        }

        return $this;
    }

    /**
     * @param string $group
     *
     * @return Customer
     */
    public function setGroup($group)
    {
        $this->setParameter('group', $group);

        return $this;
    }

    /**
     * @param string $id
     *
     * @return Customer
     */
    public function setId($id)
    {
        $this->setParameter('id', $id);

        return $this;
    }

    /**
     * @param string $language Language as ISO 639-1 code
     *
     * @return Customer
     */
    public function setLanguage($language)
    {
        $this->setParameter('language', strtolower($language));

        return $this;
    }

    /**
     * @param string $type
     *
     * @return Customer
     */
    public function setType($type)
    {
        $this->setParameter('type', $type);

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

# vim :set ts=4 sw=4 sts=4 et :