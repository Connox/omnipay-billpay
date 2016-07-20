<?php

namespace Omnipay\BillPay\Message\RequestData;

use Omnipay\BillPay\Item;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\ItemBag;
use SimpleXMLElement;

/**
 * Appends items data
 *
 * @author    Andreas Lange <andreas.lange@connox.de>
 * @copyright 2016, Connox GmbH
 * @license   MIT
 */
trait ArticleDataTrait
{
    /**
     * A list of items in this order
     *
     * @return ItemBag|null A bag containing items in this order
     *
     * @codeCoverageIgnore
     */
    abstract public function getItems();

    /**
     * @param SimpleXMLElement $data
     *
     * @throws InvalidRequestException
     */
    protected function appendArticleData(SimpleXMLElement $data)
    {
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
