<?php

namespace Omnipay\BillPay\Message\RequestData;

use Omnipay\Common\CreditCard;
use Omnipay\Common\Exception\InvalidRequestException;
use SimpleXMLElement;

/**
 * Class ShippingDetailsTrait
 *
 * @author    Andreas Lange <andreas.lange@quillo.de>
 * @copyright 2016, Quillo GmbH
 * @license   MIT
 */
trait ShippingDetailsTrait
{
    /**
     * Get the card.
     *
     * @return CreditCard
     *
     * @codeCoverageIgnore
     */
    abstract public function getCard();

    /**
     * @param string $country
     *
     * @return string|null ISO-3166-1 Alpha3
     *
     * @codeCoverageIgnore
     */
    abstract public function getCountryCode($country);

    /**
     * @param SimpleXMLElement $data
     *
     * @throws InvalidRequestException
     */
    protected function appendShippingDetails(SimpleXMLElement $data)
    {
        if ($this->hasSharedAddress()) {
            $this->fillSharedShippingDetails($data);
        } else {
            $this->fillShippingAddress($data);
        }
    }

    /**
     * @return bool
     */
    protected function hasSharedAddress()
    {
        $card = $this->getCard();

        foreach (['Title', 'FirstName', 'LastName', 'Address1', 'Address2', 'Postcode', 'City', 'Country'] as $check) {
            if ($card->{'getBilling' . $check}() !== $card->{'getShipping' . $check}()) {
                return false;
            }
        }

        return true;
    }

    /**
     * Fills the shipping address for shared addresses
     *
     * @param SimpleXMLElement $data
     */
    private function fillSharedShippingDetails(SimpleXMLElement $data)
    {
        $data->addChild('shipping_details');
        $data->shipping_details[0]['useBillingAddress'] = 1;
        $data->shipping_details[0]['salutation'] = null;
        $data->shipping_details[0]['title'] = null;
        $data->shipping_details[0]['firstName'] = null;
        $data->shipping_details[0]['lastName'] = null;
        $data->shipping_details[0]['street'] = null;
        $data->shipping_details[0]['streetNo'] = null;
        $data->shipping_details[0]['addressAddition'] = null;
        $data->shipping_details[0]['zip'] = null;
        $data->shipping_details[0]['city'] = null;
        $data->shipping_details[0]['country'] = null;
        $data->shipping_details[0]['phone'] = null;
        $data->shipping_details[0]['cellPhone'] = null;
    }

    /**
     * Fills the xml nodes with the shipping address data
     *
     * @param SimpleXMLElement $data
     */
    private function fillShippingAddress(SimpleXMLElement $data)
    {
        $card = $this->getCard();
        $data->addChild('shipping_details');
        $data->shipping_details[0]['useBillingAddress'] = 0;
        $data->shipping_details[0]['salutation'] = $card->getGender();
        $data->shipping_details[0]['title'] = $card->getShippingTitle();
        $data->shipping_details[0]['firstName'] = $card->getShippingFirstName();
        $data->shipping_details[0]['lastName'] = $card->getShippingLastName();
        $data->shipping_details[0]['street'] = $card->getShippingAddress1();
        $data->shipping_details[0]['streetNo'] = null;
        $data->shipping_details[0]['addressAddition'] = $card->getShippingAddress2();
        $data->shipping_details[0]['zip'] = $card->getShippingPostcode();
        $data->shipping_details[0]['city'] = $card->getShippingCity();
        $data->shipping_details[0]['country'] = $this->getCountryCode($card->getShippingCountry());
        $data->shipping_details[0]['phone'] = $card->getShippingPhone();
        $data->shipping_details[0]['cellPhone'] = null;
    }
}
