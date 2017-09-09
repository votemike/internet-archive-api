<?php namespace Votemike\Archive\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use stdClass;
use Votemike\Archive\Api;
use Votemike\Archive\Item;
use Votemike\Archive\MetaData;

class ApiTest extends TestCase
{
    public function testSearch200ReturnsArray()
    {
        $response = new stdClass();
        $response->docs = [];
        $body = new stdClass();
        $body->response = $response;
        $mock = new MockHandler([
            new Response(200, [], json_encode($body)),
            new RequestException("Error Communicating with Server", new Request('GET', 'test'))
        ]);

        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);
        $api = new Api($client);
        $response = $api->search('fakequerystring');
        $this->assertInternalType('array', $response);
        $this->assertEmpty($response);
    }

    public function testSearchNon200ReturnsNull()
    {
        $mock = new MockHandler([
            new Response(404),
            new RequestException("Error Communicating with Server", new Request('GET', 'test'))
        ]);

        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);
        $api = new Api($client);
        $response = $api->search('fakequerystring');
        $this->assertNull($response);
    }

    public function testSearchItemIsHydrated()
    {
        $docs = [
            [
                'collection' => ['collectiona', 'collectionb'],
                'downloads' => 123,
                'format' => ['formata', 'formatb'],
                'identifier' => 'identifier',
                'month' => 1,
                'oai_updatedate' => 'oai_updatedate',
                'title' => 'title',
            ],
        ];
        $response = new stdClass();
        $response->docs = $docs;
        $body = new stdClass();
        $body->response = $response;
        $mock = new MockHandler([
            new Response(200, [], json_encode($body)),
            new RequestException("Error Communicating with Server", new Request('GET', 'test'))
        ]);

        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);
        $api = new Api($client);
        $response = $api->search('fakequerystring');

        $this->assertInternalType('array', $response);
        $this->assertCount(1, $response);
        $this->assertContainsOnlyInstancesOf(Item::class, $response);
        $firstItem = array_pop($response);
        foreach ($docs[0] as $key => $value) {
            $this->assertEquals($value, $firstItem->{$key});
        }
    }

    public function testMetaDataNon200ReturnsItemUnchanged()
    {
        $item = new Item();
        $item->identifier = 'identifier';

        $mock = new MockHandler([
            new Response(404),
            new RequestException("Error Communicating with Server", new Request('GET', 'test'))
        ]);

        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);
        $api = new Api($client);
        $response = $api->getMetaDataForItem($item);
        $this->assertSame($item, $response);
    }

    public function testMetaData200ReturnsItemHydrated()
    {
        $metadataArray = new stdClass();
        $metadataArray->runtime = 'runtime';
        $metadata = new stdClass();
        $metadata->metadata = $metadataArray;
        $mock = new MockHandler([
            new Response(200, [], json_encode($metadata)),
            new RequestException("Error Communicating with Server", new Request('GET', 'test'))
        ]);

        $item = new Item();
        $item->identifier = 'identifier';

        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);
        $api = new Api($client);
        $response = $api->getMetaDataForItem($item);
        $this->assertInstanceOf(MetaData::class, $response->metadata);
        $this->assertEquals('runtime', $response->metadata->runtime);
    }
}
