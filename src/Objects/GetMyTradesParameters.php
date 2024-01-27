<?php

namespace bitbuyAT\Nexpay\Objects;

class GetMyTradesParameters
{
    /**
     * @var array
     */
    protected $params;

    protected $requiredParams = ['account'];

    /**
     * @param array $params
     *
     * Parameter    Req     Type                Description
     * =========    ===     ====                ===========
     * by           No      trade_id or ts      Selects if filtering and sorting is performed by trade_id or by timestamp (default: ts)
     * startIndex   No      integer             Start index for the query result row to return data from (0 = first line - default: 0)
     * maxResults   No      integer             Maximum quantity of returned result rows for one request, at most 1000 (default: 100)
     * symbols      No      string              Comma-separated list of currency symbols
     * account      Yes     string              Account number
     * sort         No      asc or desc         Trades are sorted ascending or descending (default)
     * from         No      64 bit integer      Returns trades with trade_id > specified trade_id (if by=trade_id) or returns trades with timestamp >= specified timestamp(if by=ts`)
     * till         No      64 bit integer      Returns trades with trade_id < specified trade_id (if by=trade_id) or returns trades with timestamp < specified timestamp (if by=ts)
     */
    public function __construct(array $params)
    {
        foreach ($this->requiredParams as $requiredParam) {
            if (!array_key_exists($requiredParam, $params)) {
                throw new MissingParametersException('Required parameter '.$requiredParam.' is missing.');
            }
        }

        !array_key_exists('by', $params) ? $params['by'] = 'ts' : 0;
        $this->setBy($params['by']);

        !array_key_exists('startIndex', $params) ? $params['startIndex'] = 0 : 0;
        $this->setStartIndex($params['startIndex']);

        !array_key_exists('maxResults', $params) ? $params['maxResults'] = 100 : 0;
        $this->setMaxResults($params['maxResults']);

        array_key_exists('symbols', $params) ? $this->setSymbols($params['symbols']) : 0;

        $this->setAccount($params['account']);

        array_key_exists('sort', $params) ? $this->setSort($params['sort']) : 0;
        array_key_exists('from', $params) ? $this->setFrom($params['from']) : 0;
        array_key_exists('till', $params) ? $this->setTill($params['till']) : 0;
    }

    public function setBy(string $value)
    {
        $this->params['by'] = $value;
    }

    public function setStartIndex(int $value)
    {
        $this->params['startIndex'] = $value;
    }

    public function setMaxResults(int $value)
    {
        $this->params['maxResults'] = $value;
    }

    public function setSymbols(string $value)
    {
        $this->params['symbols'] = $value;
    }

    public function setAccount(string $value)
    {
        $this->params['account'] = $value;
    }

    public function account(): string
    {
        return $this->params['account'];
    }

    public function setSort(string $value)
    {
        $this->params['sort'] = $value;
    }

    public function setFrom(int $value)
    {
        $this->params['from'] = $value;
    }

    public function setTill(int $value)
    {
        $this->params['till'] = $value;
    }

    public function getParameters(): array
    {
        return $this->params;
    }
}
