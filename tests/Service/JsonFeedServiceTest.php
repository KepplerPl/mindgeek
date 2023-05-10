<?php
declare(strict_types=1);

namespace App\Tests\Service;

use App\Service\Exceptions\MissingParameterException;
use App\Service\JsonFeedService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

final class JsonFeedServiceTest extends TestCase
{
    private readonly string $parametersAsJson;

    private readonly HttpClientInterface $client;
    private readonly ParameterBagInterface $paramBag;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        $this->client = $this->createMock(HttpClientInterface::class);
        $this->paramBag = $this->createMock(ParameterBagInterface::class);

        parent::__construct($name, $data, $dataName);
        $this->parametersAsJson = '{"feeds":{"json":"https:\/\/www.pornhub.com\/files\/json_feed_pornstars.json"},"image_storage_folder":"public\/images"}';
    }

    public function testMissingParameterInConfigException() : void
    {
        $this->expectException(MissingParameterException::class);
        $this->paramBag->expects($this->once())
            ->method('has')
            ->will($this->returnValue(false));

        $jsonFeed = new JsonFeedService($this->client, $this->paramBag);
        $jsonFeed->getFeedData();
    }

    public function testStatusNot200OKException() : void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Expected OK 200 status, instead got 500 status');
        $this->paramBag->expects($this->once())
            ->method('has')
            ->will($this->returnValue(true));

        $this->paramBag->expects($this->once())
            ->method('get')
            ->will($this->returnValue(json_decode($this->parametersAsJson, true)));

        $mockResponse = $this->getMockBuilder(ResponseInterface::class)->getMock();
        $mockResponse->method('getStatusCode')->willReturn(500);

        $this->client->expects($this->once())
            ->method('request')
            ->will($this->returnValue($mockResponse));

        $jsonFeed = new JsonFeedService($this->client, $this->paramBag);
        $jsonFeed->getFeedData();
    }


    public function testMissingParameterInConfigJsonException() : void
    {
        $this->expectException(MissingParameterException::class);
        $this->expectExceptionMessage('Expected parameter feeds.json to exist');
        $this->paramBag->expects($this->once())
            ->method('has')
            ->will($this->returnValue(true));

        $this->paramBag->expects($this->once())
            ->method('get')
            ->will($this->returnValue([]));

        $jsonFeed = new JsonFeedService($this->client, $this->paramBag);
        $jsonFeed->getFeedData();
    }

    public function testCompleteFlow(): void
    {
        $this->paramBag->expects($this->once())
            ->method('has')
            ->will($this->returnValue(true));

        $this->paramBag->expects($this->once())
            ->method('get')
            ->will($this->returnValue(json_decode($this->parametersAsJson, true)));

        $mockResponse = $this->getMockBuilder(ResponseInterface::class)->getMock();
        $mockResponse->method('getStatusCode')->willReturn(200);
        $mockResponse->method('toArray')->willReturn([]);

        $this->client->expects($this->once())
            ->method('request')
            ->will($this->returnValue($mockResponse));

        $mockResponse->expects($this->once())
            ->method('toArray')
            ->will($this->returnValue([]));

        $jsonFeed = new JsonFeedService($this->client, $this->paramBag);
        self::assertSame([], $jsonFeed->getFeedData());
    }
}