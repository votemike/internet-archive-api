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
    public function getMetaDataForItem(Item $item, string $element = null): Item
    {
        $url = 'metadata/' . $item->identifier;
        if (null !== $element) {
            $url .= '/' . $element;
        }

        $response = $this->client->request(
            'GET',
            $url,
            [
                'http_errors' => false
            ]
        );

        if (200 !== $response->getStatusCode()) {
            return $item;
        }

        $result = json_decode((string)$response->getBody());
        if (null === $element) {
            return $this->addProperties($item, $result);
        }

        if (isset($result->error)) {
            throw new Exception($result->error);
        }

        list($element) = explode('?', $element);
        $bits = explode('/', $element);
        if ($bits[0] === 'metadata') {
            if (count($bits) === 1) {
                $item->metadata = $this->createMetadata($result->result);
            }
            if (count($bits) === 2) {
                $item->metadata = $this->createMetadata([$bits[1] => $result->result]);
            }
            if (count($bits) === 3) {
                $item->metadata = $this->createMetadata([$bits[1] => [$result->result]]);
            }
        } else {
            if (count($bits) === 1) {
                $item->{$bits[0]} = $result->result;
            }
            if (count($bits) === 2) {
                $item->{$bits[0]} = [$result->result];
            }
            if (count($bits) === 3) {
                $thing = new stdClass();
                $thing->{$bits[2]} = $result->result;
                $item->{$bits[0]} = [$thing];
            }
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
                $value = $this->createMetadata($value);
            }
            $item->{$key} = $value;
        }
        return $item;
    }

    /**
     * @param stdClass|mixed[] $data
     * @return MetaData
     */
    private function createMetadata($data)
    {
        $metadata = new MetaData();
        foreach ($data as $metaDataKey => $metaDataValue) {
//            if (!property_exists($metadata, $metaDataKey)) {
//                throw new Exception($metaDataKey . ' does not appear to be part of an MetaData');
//            }
            $metadata->{$metaDataKey} = $metaDataValue;
        }
        return $metadata;
    }
}
