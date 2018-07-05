<?php

namespace Dykyi\Domain\Repository;

use Dykyi\Domain\Aggregate\Building;
use Rhumsaa\Uuid\Uuid;

interface BuildingRepositoryInterface
{
    public function add(Building $building);
    public function get(Uuid $id) : Building;
}
