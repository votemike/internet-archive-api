<?php
declare(strict_types=1);

namespace Votemike\Archive;

use Exception;
use GuzzleHttp\Client;
use stdClass;

class Api
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $query
     * @param int $page
     * @param int $rows
     * @param string[] $fields empty array means all available fields
     * @param string[] $sorts
     * @return Item[]|null returns null if API call failed
     * @throws Exception
     */
    public function search(
        string $query,
        int $page = 1,
        int $rows = 50,
        array $fields = [],
        array $sorts = []
    ): ?array {
        if (empty($query)) {
            throw new Exception('Query string must be provided');
        }

        //@TODO fields not part of API
        $response = $this->client->request(
            'GET',
            'advancedsearch.php',
            [
                'query' =>
                    [
                        'q' => $query,
                        'fl' => $fields,
                        'sort' => $sorts,
                        'rows' => $rows,
                        'page' => $page,
                        'output' => 'json',
                    ],
                'http_errors' => false
            ]
        );

        if (200 == $response->getStatusCode()) {
            $items = [];
            $info = json_decode((string)$response->getBody())->response->docs;
            foreach ($info as $values) {
                $item = new Item();
                $items[] = $this->addProperties($item, $values);
            }
            return $items;
        }

        return null;
    }

    //@TODO should we pass in the identifier instead of an Item?
    //@TODO should this be separate to an Item?
    public function getMetaDataForItem(Item $item): Item
    {
        $response = $this->client->request(
            'GET',
            'metadata/' . $item->identifier,//@TODO add limit for specific items
            [
                'http_errors' => false
            ]
        );

        if (200 == $response->getStatusCode()) {
            $item = $this->addProperties($item, json_decode((string)$response->getBody()));
        }

        return $item;
    }

    private function addProperties(Item $item, stdClass $propertiesToAdd): Item
    {
        foreach ($propertiesToAdd as $key => $value) {
//            if (!property_exists($item, $key)) {
//                throw new Exception($key . ' does not appear to be part of an Item');
//            }
            if ($key === 'metadata') {
                $metadata = new MetaData();
                foreach ($value as $metaDataKey => $metaDataValue) {
//                    if (!property_exists($metadata, $metaDataKey)) {
//                        throw new Exception($metaDataKey . ' does not appear to be part of an MetaData');
//                    }
                    $metadata->{$metaDataKey} = $metaDataValue;
                }
                $item->metadata = $metadata;
            } else {
                $item->{$key} = $value;
            }
        }
        return $item;
    }
}
