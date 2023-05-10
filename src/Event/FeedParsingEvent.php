<?php

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;

class FeedParsingEvent extends Event{
    
    public const NAME = 'feed.parsing';

    public function __construct(
        private readonly array $data
    ) {}

    public function getData(): array {
        return $this->data;
    }
}