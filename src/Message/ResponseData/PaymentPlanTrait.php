<?php

namespace Omnipay\BillPay\Message\ResponseData;

use DateTime;
use SimpleXMLElement;

/**
 * Access payment plan pof pay later in the response
 *
 * @author    Andreas Lange <andreas.lange@quillo.de>
 * @copyright 2016, Quillo GmbH
 * @license   MIT
 */
trait PaymentPlanTrait
{
    /**
     * @return SimpleXMLElement
     *
     * @codeCoverageIgnore
     */
    abstract public function getData();

    /**
     * Extracts the invoice bank account data if it exists
     *
     * @return array|null
     */
    public function getPaymentPlan()
    {
        if (!$this->hasPaymentPlan()) {
            return null;
        }

        $plan = $this->getData()->hire_purchase[0]->instl_plan[0];

        $return = [
            'num_inst' => (string)$plan['num_inst'],
            'duration' => (string)$plan->calc[0]->duration,
            'fee_percent' => (string)$plan->calc[0]->fee_percent,
            'fee_total' => bcdiv((string)$plan->calc[0]->fee_total, 100, 2),
            'pre_payment' => bcdiv((string)$plan->calc[0]->pre_payment, 100, 2),
            'total_amount' => bcdiv((string)$plan->calc[0]->total_amount, 100, 2),
            'eff_anual' => bcdiv((string)$plan->calc[0]->eff_anual, 100, 2),
            'nominal' => bcdiv((string)$plan->calc[0]->nominal, 100, 2),
            'instl' => $this->getPaymentPlanInstallments()
        ];

        return $return;
    }

    /**
     * Checks if the node has an invoice bank account node
     *
     * @return bool
     */
    public function hasPaymentPlan()
    {
        $data = $this->getData();

        return isset($data->hire_purchase) && isset($data->hire_purchase[0]->instl_plan);
    }

    /**
     * Return an array with all installments
     *
     * @return array
     */
    private function getPaymentPlanInstallments()
    {
        $installments = [];

        foreach ($this->getData()->hire_purchase[0]->instl_plan[0]->instl_list[0]->instl as $installment) {
            $installments[] = [
                'date' => DateTime::createFromFormat('Ymd', (string)$installment['date'])->format('Y-m-d'),
                'type' => (string)$installment['type'],
                'amount' => bcdiv((string)$installment, 100, 2),
            ];
        }

        return $installments;
    }
}
