<?php

namespace Omnipay\BillPay\Message\ResponseData;

use SimpleXMLElement;

/**
 * Access corrected address in the response, internal usage only
 *
 * @property SimpleXMLElement $data
 *
 * @package   Omnipay\BillPay
 * @author    Andreas Lange <andreas.lange@quillo.de>
 * @copyright 2016, Quillo GmbH
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
            'street' => (string)$this->data->corrected_address['street'],
            'streetNo' => (string)$this->data->corrected_address['streetNo'],
            'zip' => (string)$this->data->corrected_address['zip'],
            'city' => (string)$this->data->corrected_address['city'],
            'country' => (string)$this->data->corrected_address['country']
        ];
    }

    /**
     * @return bool
     */
    public function hasCorrectedAddress()
    {
        if (!$this->data instanceof SimpleXMLElement) {
            return false;
        }

        return isset($this->data->corrected_address);
    }
}
