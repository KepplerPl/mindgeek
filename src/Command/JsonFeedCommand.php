<?php

namespace App\Command;

use App\Event\FeedParsingEvent;
use App\Marshaller\FeedMarshallerInterface;
use App\Repository\FeedHistoryRepository;
use App\Repository\PornStarRepository;
use App\Service\FeedServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

#[AsCommand('app:feed:json:start', 'Starts the json feed parsing')]
class JsonFeedCommand extends Command
{

    public const FEED_TYPE = 'json';

    //feel free to changes this too as much as you want
    // i set it to 1000 because it takes too much time otherwise
    private const RECORDS_TO_PROCESS = 1000;

    public function __construct(
        private readonly FeedServiceInterface     $jsonFeedService,
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly FeedMarshallerInterface  $feedMarshaller,
        private readonly EntityManagerInterface   $entityManager,
        private readonly PornStarRepository       $pornStarRepository,
        private readonly FeedHistoryRepository    $feedHistoryRepository
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

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
            $io->success('No new items to process, exiting with success status');
            return Command::SUCCESS;
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
            if ($count > self::RECORDS_TO_PROCESS) {
                break;
            }
        }

        $io->success(sprintf('Processed "%d" records out of "%d" total records.', $count, $records['total']));

        return Command::SUCCESS;
    }
}