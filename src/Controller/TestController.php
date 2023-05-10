<?php

namespace App\Controller;

use App\Event\FeedParsingEvent;
use App\Marshaller\FeedMarshallerInterface;
use App\Repository\FeedHistoryRepository;
use App\Repository\PornStarRepository;
use App\Service\FeedServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class TestController extends AbstractController
{
    public function __construct(
        private readonly FeedServiceInterface     $jsonFeedService,
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly FeedMarshallerInterface  $feedMarshaller,
        private readonly EntityManagerInterface   $entityManager,
        private readonly PornStarRepository       $pornStarRepository,
        private readonly FeedHistoryRepository    $feedHistoryRepository
    )
    {
    }

    #[Route('/test', name: 'app_test')]
    public function index()
    {

        try {
            $records = $this->jsonFeedService->getFeedData();
        } catch (\Exception $exception) {
            return Command::FAILURE;
        }

        $records['feed_type'] = 'json';

        $event = new FeedParsingEvent($records);
        $this->eventDispatcher->dispatch($event, FeedParsingEvent::NAME);

        $latestInDatabase = $this->feedHistoryRepository->getLatestEntry();

        $latestInDatabaseTimestamp = $latestInDatabase->getCreatedAt()->getTimestamp();
        $latestInFeedTimestamp = strtotime($records['generationDate']);

        if ($latestInDatabaseTimestamp > $latestInFeedTimestamp) {
//            return new Response("No new data");
        }

        $count = 0;

        foreach ($records['items'] as $item) {
            $pornStar = $this->feedMarshaller->marshallData($item);

            if ($existingPornStar = $this->pornStarRepository->findOneBy(['external_id' => $pornStar->getExternalId()])) {
                $this->entityManager->remove($existingPornStar);
                $this->entityManager->flush();
            }

            $this->entityManager->persist($pornStar);
            if($count % 20 == 0) {
                $this->entityManager->flush();
                $this->entityManager->clear();
            }

            $count++;
            if ($count > 1000) {
                break;
            }
        }

        return new Response("Done");
    }
}
