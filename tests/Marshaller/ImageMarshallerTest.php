<?php
declare(strict_types=1);

namespace App\Tests\Marshaller;

use App\Marshaller\ImageMarshaller;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

final class ImageMarshallerTest extends TestCase
{

    private readonly HttpClientInterface $client;
    private readonly ParameterBagInterface $paramBag;
    private readonly Filesystem $fileSystem;
    private readonly array $paramBagReturnValue;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        $this->client = $this->createMock(HttpClientInterface::class);
        $this->paramBag = $this->createMock(ParameterBagInterface::class);
        $this->fileSystem = $this->createMock(Filesystem::class);
        $this->paramBagReturnValue = json_decode('{"feeds":{"json":"https:\/\/www.pornhub.com\/files\/json_feed_pornstars.json"},"image_storage_folder":"public\/images"}', true);

        $this->paramBag->expects($this->once())
            ->method('get')
            ->will($this->returnValue($this->paramBagReturnValue));

        parent::__construct($name, $data, $dataName);
    }
    public function testStatusNot200OKException() : void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Expected OK 200 status, instead got 500 status');

        $mockResponse = $this->getMockBuilder(ResponseInterface::class)->getMock();
        $mockResponse->method('getStatusCode')->willReturn(500);

        $this->client->expects($this->once())
            ->method('request')
            ->will($this->returnValue($mockResponse));

        $jsonFeed = new ImageMarshaller($this->client, $this->paramBag, $this->fileSystem);
        $jsonFeed->marshallImage('example.com');
    }
}