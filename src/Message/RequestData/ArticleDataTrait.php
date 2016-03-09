<?php

namespace Omnipay\BillPay\Message\RequestData;

use Omnipay\BillPay\Item;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\ItemBag;
use SimpleXMLElement;

/**
 * Appends items data
 *
 * @author    Andreas Lange <andreas.lange@quillo.de>
 * @copyright 2016, Quillo GmbH
 * @license   MIT
 */
trait ArticleDataTrait
{
    /**
     * A list of items in this order
     *
     * @return ItemBag|null A bag containing items in this order
     */
    abstract public function getItems();

    /**
     * @param SimpleXMLElement $data
     *
     * @throws InvalidRequestException
     */
    protected function appendArticleData(SimpleXMLElement $data)
    {
        if ($this->getItems() === null || $this->getItems()->count() === 0) {
            throw new InvalidRequestException('This request requires items.');
        }

        $data->addChild('article_data');

        foreach ($this->getItems()->all() as $pos => $item) {
            if (!$item instanceof Item) {
                throw new InvalidRequestException('All items must be of instance of ' . Item::class);
            }

            /* @noinspection DisconnectedForeachInstructionInspection */
            $data->article_data[0]->addChild('article');
            $data->article_data[0]->article[$pos]['articleid'] = $item->getId();
            $data->article_data[0]->article[$pos]['articlequantity'] = $item->getQuantity();
            $data->article_data[0]->article[$pos]['articlename'] = $item->getName();
            $data->article_data[0]->article[$pos]['articledescription'] = $item->getDescription();
            $data->article_data[0]->article[$pos]['articleprice'] = bcmul($item->getPriceNet(), 100, 0);
            $data->article_data[0]->article[$pos]['articlepricegross'] = bcmul($item->getPrice(), 100, 0);
        }
    }
}
