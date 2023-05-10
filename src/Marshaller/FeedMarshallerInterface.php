<?php

namespace App\Marshaller;

use App\Entity\PornStar;

interface FeedMarshallerInterface
{
    public function marshallData(array $data) : PornStar;
}