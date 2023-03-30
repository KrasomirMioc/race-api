<?php

namespace App\Repository;

use App\Entity\Result;
use App\Enum\DistanceEnum;
use App\Interface\ResultRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ResultRepository extends ServiceEntityRepository implements ResultRepositoryInterface
{
    private EntityRepository $ormRepository;

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        ManagerRegistry $registry
    )
    {
        parent::__construct($registry, Result::class);
        $this->ormRepository = $this->entityManager->getRepository(Result::class);
    }

    public function getResultsByDistance(string $distance): array
    {
        return $this->ormRepository->findBy(['distance' => $distance]);
    }

    public function getAvgTimeByDistance(string $distance): string
    {
        $times = $this->createQueryBuilder('result')
            ->andWhere('result.distance = :distance')
            ->setParameter('distance', $distance)
            ->select('result.finishTime')
            ->getQuery()
            ->getResult();

        $timesCount = count($times);

        $seconds = [];
        foreach ($times as $time) {
            $seconds[] = $this->convertToSeconds($time['finishTime']);
        }

        // convert number of seconds into time format
        $averageSeconds = array_sum($seconds) / $timesCount;

        // return number of seconds formatted as time
        return gmdate('H:i:s', $averageSeconds);
    }

    private function convertToSeconds(string $time): float|int
    {
        if (!str_contains($time, ':')) {
            return 0;
        }

        $seconds = 0;
        $timeElements = explode(':', $time);
        rsort($timeElements);

        foreach ($timeElements as $key => $element) {
            if ($key == 0) {
                $seconds += (int) $element;
                continue;
            }
            $seconds += ($element * pow(60, $key));
        }

        return $seconds;
    }

    public function getResultsForLongDistanceOrderedByFinishTime(): array
    {
        return $this->ormRepository->findBy(['distance' => DistanceEnum::Long->value], ['finishTime' => 'ASC']);
    }

    public function getResultsForLongDistanceOrderedByAgeCategoryAndFinishTime(): array
    {
        return $this->ormRepository->findBy(
            ['distance' => DistanceEnum::Long->value],
            [
                'ageCategory' => 'ASC',
                'finishTime' => 'ASC'
            ]
        );
    }
}