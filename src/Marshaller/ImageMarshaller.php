<?php
declare(strict_types=1);

namespace App\Marshaller;

use Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class ImageMarshaller
{
    private const IMAGE_MIME_TYPES = [
        'image/gif',
        'image/jpeg',
        'image/png',
        'image/jpg',
    ];

    private readonly string $fullPath;

    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly ParameterBagInterface $parameterBag,
        private readonly Filesystem $filesystem
    ) {
        $this->fullPath = $this->parameterBag->get('app.mind_geek')['image_storage_folder'];
    }

    /**
     * @throws TransportExceptionInterface
     * @throws Exception
     */
    public function marshallImage(string $imageUrl) : string
    {
        $response = $this->getResponse($imageUrl);
        $body = $response->getContent();
        $extension = $this->getFileExtension($response);
        $randName = $this->fullPath . DIRECTORY_SEPARATOR . bin2hex(random_bytes(20)) . '.' . $extension;
        $this->filesystem->dumpFile($randName, $body);
        $sha1Hash = sha1_file($randName);
        $nameAndExtension = $sha1Hash . '.' . $extension;
        $fullPath = $this->fullPath . DIRECTORY_SEPARATOR . $nameAndExtension;
        if($this->filesystem->exists($fullPath)){
            $this->filesystem->remove($fullPath);
            return $nameAndExtension;
        }

        $this->filesystem->rename($randName, $fullPath);

        return $nameAndExtension;
    }

    /**
     * @throws TransportExceptionInterface
     * @throws Exception
     */
    private function getResponse(string $url): ResponseInterface
    {
        $response = $this->client->request(
            'GET',
            $url
        );

        $statusCode = $response->getStatusCode();

        if($statusCode !== 200) {
            throw new Exception(sprintf('Expected OK 200 status, instead got %d status', $statusCode));
        }

        return $response;
    }

    private function getFileExtension($response): string
    {
        $headers = $response->getHeaders();
        if(isset($headers['content-type']) && count($headers['content-type']) > 0) {
            foreach($headers['content-type'] as $mineType) {
                if(in_array($mineType, self::IMAGE_MIME_TYPES)) {
                    $parts = explode('/', $mineType);
                    return $parts[1];
                }
            }
        }

        // return some default value;
        return 'jpeg';
    }
}