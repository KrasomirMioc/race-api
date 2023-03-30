<?php

namespace App\Interface;

interface ResultRepositoryInterface
{
    public function getResultsByDistance(string $distance): array;

    public function getAvgTimeByDistance(string $distance): string;

    public function getResultsForLongDistanceOrderedByFinishTime(): array;

    public function getResultsForLongDistanceOrderedByAgeCategoryAndFinishTime(): array;
}