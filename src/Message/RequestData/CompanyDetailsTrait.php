<?php

namespace Omnipay\BillPay\Message\RequestData;

use Omnipay\BillPay\Company;
use Omnipay\BillPay\Message\AuthorizeRequest;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\AbstractRequest;
use SimpleXMLElement;

/**
 * Class CompanyDetailsTrait.
 */
trait CompanyDetailsTrait
{
    public function getCompanyDetails()
    {
        return $this->getParameter('companyDetails');
    }

    /**
     * Sets the company detail information.
     *
     * @param Company $company
     *
     * @return AuthorizeRequest
     */
    public function setCompanyDetails($company)
    {
        return $this->setParameter('companyDetails', $company);
    }

    /**
     * @param SimpleXMLElement $data
     *
     * @throws InvalidRequestException
     */
    protected function appendCompanyDetails(SimpleXMLElement $data)
    {
        $data->addChild('company_details');
        $this->appendCompanyDetailsAdditional($data, $this->getCompanyDetails());
    }

    /**
     * Get a single parameter.
     *
     * @param string $key The parameter key
     *
     * @return mixed
     *
     * @codeCoverageIgnore
     */
    abstract protected function getParameter($key);

    /**
     * Set a single parameter.
     *
     * @param string $key   The parameter key
     * @param mixed  $value The value to set
     *
     * @return AbstractRequest Provides a fluent interface
     *
     * @codeCoverageIgnore
     */
    abstract protected function setParameter($key, $value);

    /**
     * Fills additional data.
     *
     * @param SimpleXMLElement $data
     * @param Company         $company
     */
    private function appendCompanyDetailsAdditional(SimpleXMLElement $data, Company $company)
    {
        $data->company_details[0]['name'] = $company->getName();
        $data->company_details[0]['legalForm'] = $company->getLegalForm();
        $data->company_details[0]['registerNumber'] = $company->getRegisterNumber();
        $data->company_details[0]['holderName'] = $company->getHolderName();
        $data->company_details[0]['taxNumber'] = $company->getTaxNumber();
    }
}
