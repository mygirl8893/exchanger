<?php

/*
 * This file is part of Exchanger.
 *
 * (c) Florian Voutzinos <florian@voutzinos.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Exchanger\Service;

use Exchanger\Contract\ExchangeRateQuery;
use Exchanger\Contract\HistoricalExchangeRateQuery;
use Exchanger\ExchangeRate;
use Exchanger\StringUtil;

/**
 * WebserviceX Service.
 *
 * @author Florian Voutzinos <florian@voutzinos.com>
 */
class WebserviceX extends Service
{
    const URL = 'http://www.webservicex.net/currencyconvertor.asmx/ConversionRate?FromCurrency=%s&ToCurrency=%s';

    /**
     * {@inheritdoc}
     */
    public function getExchangeRate(ExchangeRateQuery $exchangeQuery)
    {
        $currencyPair = $exchangeQuery->getCurrencyPair();

        $url = sprintf(self::URL, $currencyPair->getBaseCurrency(), $currencyPair->getQuoteCurrency());
        $content = $this->request($url);

        return new ExchangeRate((string) StringUtil::xmlToElement($content), new \DateTime());
    }

    /**
     * {@inheritdoc}
     */
    public function supportQuery(ExchangeRateQuery $exchangeQuery)
    {
        return !$exchangeQuery instanceof HistoricalExchangeRateQuery;
    }
}
