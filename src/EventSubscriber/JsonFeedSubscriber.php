<?php
declare(strict_types=1);

namespace App\EventSubscriber;

use App\Command\JsonFeedCommand;
use App\Entity\FeedHistory;
use App\Event\FeedParsingEvent;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class JsonFeedSubscriber implements EventSubscriberInterface
{

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly LoggerInterface $logger
    ){}

    /**
     * @throws \Exception
     */
    public function onFeedParsing(FeedParsingEvent $event): void
    {
        $data = $event->getData();
        if(!isset($data['feed_type']) || $data['feed_type'] != JsonFeedCommand::FEED_TYPE) {
            return;
        }

        try {
            $date = new \DateTime();
            $date->setTimestamp(strtotime($data['generationDate'])); // i'm suuuuure this will never break
        } catch (\Exception $e) {
            $this->logger->debug($e->getMessage(), [
                'trace' => $e->getTrace()
            ]);

            throw $e;
        }

        $feedHistory = new FeedHistory();
        $feedHistory->setCreatedAt(new \DateTimeImmutable());
        $feedHistory->setSite($data['site']);
        $feedHistory->setType($data['feed_type']);
        $feedHistory->setItemsCount($data['itemsCount']);
        $feedHistory->setDate($date);

        $this->entityManager->persist($feedHistory);
        $this->entityManager->flush();
    }

    public static function getSubscribedEvents(): array
    {
        return [
            FeedParsingEvent::NAME => 'onFeedParsing',
        ];
    }
}
