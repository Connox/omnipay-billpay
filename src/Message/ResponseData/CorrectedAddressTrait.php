<?php

namespace Omnipay\BillPay\Message\ResponseData;

use SimpleXMLElement;

/**
 * Access corrected address in the response, internal usage only
 *
 * @author    Andreas Lange <andreas.lange@connox.de>
 * @copyright 2016, Connox GmbH
 * @license   MIT
 */
trait CorrectedAddressTrait
{
    /**
     * Gets the corrected address
     *
     * @return array|null
     */
    public function getCorrectedAddress()
    {
        if (!$this->hasCorrectedAddress()) {
            return null;
        }

        return [
            'street' => (string)$this->getData()->corrected_address['street'],
            'streetNo' => (string)$this->getData()->corrected_address['streetNo'],
            'zip' => (string)$this->getData()->corrected_address['zip'],
            'city' => (string)$this->getData()->corrected_address['city'],
            'country' => (string)$this->getData()->corrected_address['country'],
        ];
    }

    /**
     * @return SimpleXMLElement
     *
     * @codeCoverageIgnore
     */
    abstract public function getData();

    /**
     * @return bool
     */
    public function hasCorrectedAddress()
    {
        return isset($this->getData()->corrected_address);
    }
}
