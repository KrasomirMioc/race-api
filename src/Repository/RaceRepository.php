<?php

namespace App\Repository;

use App\Entity\Race;
use App\Entity\Result;
use App\Enum\DistanceEnum;
use App\Interface\RaceRepositoryInterface;
use App\Interface\ResultRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class RaceRepository implements RaceRepositoryInterface
{
    private Race $race;

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly ResultRepositoryInterface $resultRepository

    )
    {
        $this->race = new Race();
    }

    public function import(UploadedFile $file, array $properties): Race
    {
        $rows = $this->getFileContent($file);

        $this->race->setTitle($properties['title']);
        $this->race->setRaceDate($properties['raceDate']);

        $this->entityManager->persist($this->race);

        $this->handleRaceResults($rows);

        $this->entityManager->flush();

        $this->handlePlacements();
        $this->handleAvgFinishTimes();

        return $this->race;
    }

    private function getFileContent(UploadedFile $file): array
    {
        $rows = [];
        if (($handle = fopen($file->getRealPath(), "r")) !== FALSE) {
            $i = 0;
            while (($data = fgetcsv($handle, null, ",")) !== FALSE) {
                $i++;
                if ($i == 1) { continue; }
                $rows[] = $data;
            }
            fclose($handle);
        }

        return $rows;
    }

    private function handleRaceResults(array $rows): void
    {
        foreach ($rows as $row) {
            $result = new Result();
            $result->setFullName($row[0]);
            $result->setDistance(DistanceEnum::from($row[1]));
            $result->setFinishTime($row[2]);
            $result->setAgeCategory($row[3]);

            $this->entityManager->persist($result);
            $this->race->addResult($result);
        }
    }

    private function handlePlacements()
    {
        $this->handleOverallPlacements();
        $this->handleAgeCategoryPlacement();
    }

    private function handleAvgFinishTimes(): void
    {
        foreach (DistanceEnum::toArray() as $enum) {
            $result = $this->resultRepository->getAvgTimeByDistance($enum->value);

            if ($enum->value == DistanceEnum::Medium->value) {
                $this->race->setAvgTimeForMediumDistance($result);
            }
            if ($enum->value == DistanceEnum::Long->value) {
                $this->race->setAvgTimeForLongDistance($result);
            }
        }
    }

    private function handleOverallPlacements(): void
    {
        $results = $this->resultRepository->getResultsForLongDistanceOrderedByFinishTime();

        array_map(function ($result, $key) {

            $placement = $key + 1;
            /** @var Result $result */
            $result->setOverallPlace($placement);

        }, $results, array_keys($results));

        $this->entityManager->flush();
    }

    private function handleAgeCategoryPlacement()
    {
        /** @var Result[] $results */
        $results = $this->resultRepository->getResultsForLongDistanceOrderedByAgeCategoryAndFinishTime();

        $ageCategoriesResults = array_map(function ($result) {
            $categoryGroup[$result->getAgeCategory()] = $result;

            return $categoryGroup;
        }, $results);

        $categoryResults = $this->groupByKey($ageCategoriesResults);

        foreach ($categoryResults as $keyCategoryResults) {
            foreach ($keyCategoryResults as $index => $result) {
                $placement = $index + 1;
                /** @var Result $result */
                $result->setAgeCategoryPlace($placement);
            }
        }

        $this->entityManager->flush();
    }

    private function groupByKey(array $data): array
    {
        $result = [];

        foreach ($data as $values) {
            foreach ($values as $key => $value) {
                $result[$key][] = $value;
            }
        }

        return $result;
    }
}