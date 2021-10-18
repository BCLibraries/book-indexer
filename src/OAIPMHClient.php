<?php

namespace Bclib\GetBooksFromAlma;

use Phpoaipmh\Endpoint;
use Phpoaipmh\RecordIteratorInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface;

class OAIPMHClient
{
    private \Phpoaipmh\Client $oai_client;
    private Endpoint $endpoint;
    private AdapterInterface $cache;

    private const RESUMPTION_TOKEN_CACHE_KEY = 'oaipmh.resumption_token';

    // @todo make listRecords arguments configurable
    private const METADATA_PREFIX = 'marc21';
    private const SET = 'bc_print_books';

    /**
     * Constructor
     *
     * @param string $api_base
     * @param string $code
     * @param AdapterInterface $cache
     * @throws \Exception
     */
    public function __construct(string $api_base, string $code, AdapterInterface $cache)
    {
        $url = "$api_base/view/oai/$code/request";
        $this->oai_client = new \Phpoaipmh\Client($url);
        $this->endpoint = new Endpoint($this->oai_client);
        $this->cache = $cache;
    }

    public function resumeList(?\DateTimeInterface $from = null, ?\DateTimeInterface $until = null): \Generator
    {
        $token = $this->cache->getItem(self::RESUMPTION_TOKEN_CACHE_KEY)->get();
        foreach ($this->list($from, $until, $token) as $item) {
            yield $item;
        }
    }

    public function list(?\DateTimeInterface $from = null, ?\DateTimeInterface $until = null, ?string $resumption_token = null): \Generator
    {
        $recs = $this->endpoint->listRecords(self::METADATA_PREFIX, $from, $until, self::SET, $resumption_token);
        foreach ($recs as $rec) {
            $this->handleResumptionTokenCaching($recs, $resumption_token);
            yield $rec;
        }
    }

    /**
     * Cache the resumption token to catch up on interrupted ingests
     *
     * @param RecordIteratorInterface $recs
     * @param string|null $old_token
     * @throws \Psr\Cache\InvalidArgumentException
     */
    private function handleResumptionTokenCaching(RecordIteratorInterface $recs, ?string $old_token): void
    {
        // Cache the token if it has changed.
        $new_token = $recs->getResumptionToken();
        if ($new_token !== $old_token) {
            $item = $this->cache->getItem(self::RESUMPTION_TOKEN_CACHE_KEY);
            $item->set($new_token);
            $this->cache->save($item);
        }
    }

}