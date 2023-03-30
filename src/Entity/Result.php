<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Common\Filter\SearchFilterInterface;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Enum\DistanceEnum;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 */
#[ApiResource(
    operations: [
        new Patch()
    ]
)]
#[ApiResource(
    uriTemplate: '/races/{id}/results',
    operations: [new GetCollection()],
    uriVariables: [
        'id' => new Link(
            fromProperty: 'results',
            fromClass: Race::class
        )
    ]
)]
#[ApiFilter(
    SearchFilter::class,
    properties: [
        'fullName' => SearchFilterInterface::STRATEGY_PARTIAL,
        'distance' => SearchFilterInterface::STRATEGY_EXACT,
        'ageCategory' => SearchFilterInterface::STRATEGY_PARTIAL,
    ],
)]
#[ApiFilter(
    OrderFilter::class,
    properties: ['fullName', 'finishTime', 'distance', 'ageCategory', 'overallPlace', 'ageCategoryPlace']
)]
#[ORM\Entity]
class Result
{
    /** @var int|null $id ID race result */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    /** @var string $fullName Full name of a racer */
    #[ORM\Column]
    #[Assert\NotBlank]
    private string $fullName = '';

    /** @var string $finishTime Finish time */
    #[ORM\Column(type: Types::STRING)]
    #[Assert\NotBlank]
    private string $finishTime;

    /** @var DistanceEnum $distance Distance - can be medium|long */
    #[ORM\Column(
        type: Types::STRING,
        enumType: DistanceEnum::class
    )]
    #[Assert\NotBlank]
    private DistanceEnum $distance = DistanceEnum::Medium;

    /** @var string $ageCategory Age category of a racer */
    #[ORM\Column]
    #[Assert\NotBlank]
    private string $ageCategory = '';

    /** @var int $overallPlace Placement for all results together */
    #[ORM\Column(type: Types::INTEGER)]
    #[Assert\NotBlank]
    #[Assert\Type(
        type: 'integer',
        message: ' THe value {{ value }} is not a valid {{ type}}',
    )]
    private int $overallPlace = 0;

    /** @var int $ageCategoryPlace Placement for each age category separately */
    #[ORM\Column(type: Types::INTEGER)]
    #[Assert\NotBlank]
    #[Assert\Type(
        type: 'integer',
        message: ' THe value {{ value }} is not a valid {{ type}}',
    )]
    private int $ageCategoryPlace = 0;

    /** @var Race $race Relation to Race */
    #[ORM\ManyToOne(targetEntity: Race::class, inversedBy: "results")]
    #[Assert\NotBlank]
    private Race $race;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getFullName(): string
    {
        return $this->fullName;
    }

    /**
     * @param string $fullName
     */
    public function setFullName(string $fullName): void
    {
        $this->fullName = $fullName;
    }

    /**
     * @return string
     */
    public function getFinishTime(): string
    {
        return $this->finishTime;
    }

    /**
     * @param string $finishTime
     */
    public function setFinishTime(string $finishTime): void
    {
        $this->finishTime = $finishTime;
    }

    /**
     * @return DistanceEnum
     */
    public function getDistance(): DistanceEnum
    {
        return $this->distance;
    }

    /**
     * @param DistanceEnum $distance
     */
    public function setDistance(DistanceEnum $distance): void
    {
        $this->distance = $distance;
    }

    /**
     * @return string
     */
    public function getAgeCategory(): string
    {
        return $this->ageCategory;
    }

    /**
     * @param string $ageCategory
     */
    public function setAgeCategory(string $ageCategory): void
    {
        $this->ageCategory = $ageCategory;
    }

    /**
     * @return int
     */
    public function getOverallPlace(): int
    {
        return $this->overallPlace;
    }

    /**
     * @param int $overallPlace
     */
    public function setOverallPlace(int $overallPlace): void
    {
        $this->overallPlace = $overallPlace;
    }

    /**
     * @return int
     */
    public function getAgeCategoryPlace(): int
    {
        return $this->ageCategoryPlace;
    }

    /**
     * @param int $ageCategoryPlace
     */
    public function setAgeCategoryPlace(int $ageCategoryPlace): void
    {
        $this->ageCategoryPlace = $ageCategoryPlace;
    }

    /**
     * @return Race
     */
    public function getRace(): Race
    {
        return $this->race;
    }

    /**
     * @param Race $race
     */
    public function setRace(Race $race): void
    {
        $this->race = $race;
    }
}