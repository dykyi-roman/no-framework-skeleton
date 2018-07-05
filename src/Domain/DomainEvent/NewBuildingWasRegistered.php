<?php


namespace Dykyi\Domain\DomainEvent;

use Prooph\EventSourcing\AggregateChanged;

final class NewBuildingWasRegistered extends AggregateChanged
{
    public function name() : string
    {
        return $this->payload['name'];
    }
}
