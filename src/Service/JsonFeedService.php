<?php
declare(strict_types=1);

namespace App\Service;

use App\Service\Exceptions\MissingParameterException;
use Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class JsonFeedService implements FeedServiceInterface {

    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly ParameterBagInterface $parameterBag
    ) {}

    /**
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws MissingParameterException
     */
    public function getFeedData() : array
    {
        if(!$this->parameterBag->has('app.mind_geek')) {
            throw new MissingParameterException("Missing parameter key app.mind_geek");
        }

        $parameter = $this->parameterBag->get('app.mind_geek');

        if(!isset($parameter['feeds']['json'])) {
            throw new MissingParameterException("Expected parameter feeds.json to exist");
        }

        return $this->getContentAsArray($parameter['feeds']['json']);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    private function getContentAsArray(string $url) : array
    {
        $response = $this->client->request(
            'GET',
            $url
        );

        $statusCode = $response->getStatusCode();

        if($statusCode !== 200) {
            throw new Exception(sprintf("Expected OK 200 status, instead got %d status", $statusCode));
        }

        return $response->toArray();
    }
}
