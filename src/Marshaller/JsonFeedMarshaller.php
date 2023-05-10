<?php
declare(strict_types=1);

namespace App\Marshaller;

use App\Entity\Attributes;
use App\Entity\PornStar;
use App\Entity\Stats;
use App\Entity\Thumbnail;
use App\Entity\ThumbnailImage;
use App\Repository\ThumbnailUrlRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class JsonFeedMarshaller implements FeedMarshallerInterface
{

    public function __construct(
        private readonly ImageMarshaller        $imageMarshaller,
        private readonly LoggerInterface        $logger,
        private readonly ThumbnailUrlRepository $thumbnailUrlRepository,
        private readonly EntityManagerInterface $entityManager
    )
    {
    }

    public function marshallData($data): PornStar
    {
        $pornStar = new PornStar();
        $attributes = null;
        $stats = null;
        if (isset($data['attributes'])) {
            $attributes = $this->marshallAttributes($data['attributes']);
        }

        if (isset($data['attributes']['stats'])) {
            $stats = $this->marshallStats($data['attributes']['stats']);
        }

        if (!is_null($attributes) && !is_null($stats)) {
            $attributes->setStats($stats);
            $pornStar->setAttributes($attributes);
        }

        $thumbnails = $this->marshallThumbnail($data['thumbnails']);
        foreach ($thumbnails as $thumbnail) {
            $pornStar->addThumbnail($thumbnail);
        }

        $pornStar->setExternalId($data['id'] ?? null);
        $pornStar->setName($data['name'] ?? null);
        $pornStar->setLicense($data['license'] ?? null);
        $pornStar->setWlStatus($data['wlStatus'] ?? null);
        $pornStar->setAliases($data['aliases'] ?? null);
        $pornStar->setLink($data['link'] ?? null);

        return $pornStar;
    }

    private function marshallThumbnail(array $thumbnails): array
    {
        $thumbnailObjects = [];
        foreach ($thumbnails as $thumbnail) {

            $thumbnailObject = new Thumbnail();
            $thumbnailObject->setHeight($thumbnail['height'] ?? null);
            $thumbnailObject->setWidth($thumbnail['width'] ?? null);
            $thumbnailObject->setType($thumbnail['type'] ?? null);

            foreach ($thumbnail['urls'] as $url) {
                try {
                    $thumbnailObject->setTumbnailImage(
                        $this->marshallThumbnailUrl($url)
                    );
                } catch (\Exception|\Throwable $exception) {
                    // log errors and continue
                    $this->logger->debug($exception->getMessage(), [
                        'trace' => $exception->getTrace(),
                    ]);
                    continue;
                }
            }

            $thumbnailObjects[] = $thumbnailObject;
        }

        return $thumbnailObjects;
    }

    /**
     * @throws TransportExceptionInterface
     */
    private function marshallThumbnailUrl(string $url): ThumbnailImage
    {
        $imageName = $this->imageMarshaller->marshallImage($url);

        $thumbnail = $this->thumbnailUrlRepository->findOneBy(['url' => $imageName]);
        if ($thumbnail) {
            return $thumbnail;
        }

        $thumbnailUrl = new ThumbnailImage();
        $thumbnailUrl->setName($imageName);
        $this->entityManager->persist($thumbnailUrl);
        $this->entityManager->flush();

        return $thumbnailUrl;
    }

    private function marshallAttributes($dataAttributes): Attributes
    {
        $attributes = new Attributes();

        $attributes->setHairColor($dataAttributes['hairColor'] ?? null);
        $attributes->setEthnicity($dataAttributes['ethnicity'] ?? null);
        $attributes->setTattoos(boolval($dataAttributes['tattoos'] ?? null));
        $attributes->setPiercings(boolval($dataAttributes['piercings'] ?? null));
        $attributes->setBreastSize($dataAttributes['breastSize'] ?? null);
        $attributes->setBreastType($dataAttributes['breastType'] ?? null);
        $attributes->setGender($dataAttributes['gender'] ?? null);
        $attributes->setOrientation($dataAttributes['orientation'] ?? null);
        $attributes->setAge($dataAttributes['age'] ?? null);

        return $attributes;
    }

    private function marshallStats($dataStats): Stats
    {
        $stats = new Stats();

        $stats->setSubscriptions($dataStats['subscriptions'] ?? null);
        $stats->setMonthlySearches($dataStats['monthlySearches'] ?? null);
        $stats->setViews($dataStats['views'] ?? null);
        $stats->setVideosCount($dataStats['videosCount'] ?? null);
        $stats->setPremiumVideosCount($dataStats['premiumVideosCount'] ?? null);
        $stats->setWhiteLabelVideoCount($dataStats['whiteLabelVideoCount'] ?? null);
        $stats->setRank($dataStats['rank'] ?? null);
        $stats->setRankPremium($dataStats['rankPremium'] ?? null);
        $stats->setRankwl($dataStats['rankWl'] ?? null);

        return $stats;
    }
}