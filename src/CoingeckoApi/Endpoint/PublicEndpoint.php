<?php

namespace madmis\CoingeckoApi\Endpoint;

use madmis\CoingeckoApi\Api;
use madmis\ExchangeApi\Endpoint\AbstractEndpoint;
use madmis\ExchangeApi\Endpoint\EndpointInterface;
use madmis\ExchangeApi\Exception\ClientException;

/**
 * Class PublicEndpoint
 * @package madmis\CoingeckoApi\Endpoint
 */
class PublicEndpoint extends AbstractEndpoint implements EndpointInterface
{
    /**
     * https://api.coingecko.com/api/v3/simple/supported_vs_currencies
     *
     * @return array
     */
    public function supportedVsCurrencies()
    {
        $response = $this->sendRequest(
            Api::GET,
            $this->getApiUrn(['simple', 'supported_vs_currencies'])
        );

        return $response;
    }

    /**
     * https://api.coingecko.com/api/v3/simple/supported_vs_currencies
     *
     * @return array
     */
    public function coinsList()
    {
        $response = $this->sendRequest(
            Api::GET,
            $this->getApiUrn(['coins', 'list'])
        );

        return $response;
    }

    /**
     * https://api.coingecko.com/api/v3/coins/energi
     *
     * curl -X GET "https://api.coingecko.com/api/v3/coins/energi
     *     ?localization=true
     *     &tickers=true
     *     &market_data=true
     *     &community_data=true
     *     &developer_data=true
     *     &sparkline=false
     *
     * @param $coin
     * @param string $quote
     * @return array
     */
    public function coinDetails($coin, $quote = Api::QUOTE_USD, $params = [])
    {
        $query = array_merge(
            [
                'vs_currencies' => $quote,
            ],
            $params
        );

        $response = $this->sendRequest(
            Api::GET,
            $this->getApiUrn(['coins', $coin]),
            ['query' => $query]
        );

        return $response;
    }

    /**
     * curl -X GET "https://api.coingecko.com/api/v3/coins/energi/tickers
     *     ?include_exchange_logo=true
     *     &page={int}
     *
     * @param $coin
     * @return array
     */
    public function coinTickers($coin, $params = [])
    {
        $response = $this->sendRequest(
            Api::GET,
            $this->getApiUrn(['coins', $coin, 'tickers']),
            ['query' => $params]
        );

        return $response;
    }

    /**
     * https://api.coingecko.com/api/v3/coins/energi/market_chart?vs_currency=chf&days=max
     *
     * @param $coin
     * @param string $quote
     * @param string $days
     *
     * @return array
     */
    public function marketChart($coin, $quote = Api::QUOTE_USD, $days = 'max')
    {
        $response = $this->sendRequest(
            Api::GET,
            $this->getApiUrn(['coins', $coin, 'market_chart']),
            [
                'query' => [
                    'vs_currency' => $quote,
                    'days' => $days,
                ],
            ]
        );

        return $response;
    }

    /**
     * https://api.coingecko.com/api/v3/simple/price
     *     ?ids=energi
     *     &vs_currencies=chf
     *     &include_24hr_vol=true
     *     &include_24hr_change=true
     *     &include_last_updated_at=true
     *
     * @param $coin
     * @param string $quote
     * @param array $params
     *
     * @return array
     */
    public function simplePrice($coin, $quote = Api::QUOTE_USD, $params = [])
    {
        $query = array_merge([
            'vs_currencies' => $quote,
            'ids' => $coin,
        ],
            $params
        );

        $response = $this->sendRequest(
            Api::GET,
            $this->getApiUrn(['simple', 'price']),
            ['query' => $query]
        );

        return $response;
    }

    /**
     * @param string $method Http::GET|POST
     * @param string $uri
     * @param array $options Request options to apply to the given
     *                                  request and to the transfer.
     * @return array response
     * @throws ClientException
     */
    protected function sendRequest(string $method, string $uri, array $options = []): array
    {
        $request = $this->client->createRequest($method, $uri);

        return $this->processResponse(
            $this->client->send($request, $options)
        );

    }
}
