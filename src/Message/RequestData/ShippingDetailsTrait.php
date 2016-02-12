<?php

namespace Omnipay\BillPay\Message\RequestData;

use Omnipay\BillPay\Message\AuthorizeRequest;
use Omnipay\Common\Exception\InvalidRequestException;
use SimpleXMLElement;

/**
 * Class ShippingDetailsTrait
 *
 * @package   Omnipay\BillPay
 * @author    Andreas Lange <andreas.lange@quillo.de>
 * @copyright 2016, Quillo GmbH
 * @license   MIT
 */
trait ShippingDetailsTrait
{
    /**
     * @param SimpleXMLElement $data
     *
     * @throws InvalidRequestException
     */
    protected function appendShippingDetails(SimpleXMLElement $data)
    {
        /** @var AuthorizeRequest $this */

        if (!$this instanceof AuthorizeRequest) {
            throw new InvalidRequestException('Trait can only be used inside instance of ' . AuthorizeRequest::class);
        }

        $card = $this->getCard();

        if ($card === null) {
            throw new InvalidRequestException('Credit card and customer object required for address details.');
        }

        $same = 1;

        foreach (['Title', 'FirstName', 'LastName', 'Address1', 'Address2', 'Postcode', 'City', 'Country'] as $check) {
            if ($card->{'getBilling' . $check}() !== $card->{'getShipping' . $check}()) {
                $same = 0;
            }
        }

        $data->addChild('shipping_details');

        if ($same) {
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
        } else {
            $data->shipping_details[0]['useBillingAddress'] = 0;
            $data->shipping_details[0]['salutation'] = null;
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
}
