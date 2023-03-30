<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Common\Filter\SearchFilterInterface;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\OpenApi\Model\Operation;
use ApiPlatform\OpenApi\Model\RequestBody;
use App\Controller\ImportRaceAction;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/** A Race model */
#[Vich\Uploadable]
#[ApiResource]
#[ApiFilter(
    SearchFilter::class,
    properties: [
        'title' => SearchFilterInterface::STRATEGY_PARTIAL,
    ]
)]
#[ApiFilter(
    OrderFilter::class,
    properties: ['title', 'raceDate', 'avgTimeForMediumDistance', 'avgTimeForLongDistance']
)]
#[Post(
    uriTemplate: '/races/import',
    defaults: ['_api_receive' => false],
    controller: ImportRaceAction::class,
    openapi: new Operation(
        requestBody: new RequestBody(
            content: new \ArrayObject([
                'multipart/form-data' => [
                    'schema' => [
                        'type' => 'object',
                        'properties' => [
                            'file' => [
                                'type' => 'string',
                                'format' => 'binary',
                            ],
                            'title' => [
                                'type' => 'string',
                            ],
                            'raceDate' => [
                                'type' => 'datetime',
                            ]
                        ]
                    ]
                ]
            ])
        )
    ),
    validationContext: ['groups' => ['media_object_create']],
    deserialize: 'false'
)]
#[GetCollection]
#[ORM\Entity]
class Race
{
    /** @var int|null $id ID of race */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    /** @var string $title Race title */
    #[ORM\Column(type: Types::STRING)]
    #[Assert\NotBlank]
    private string $title = '';

    /** @var DateTimeInterface|null Date and time of the race */
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotNull]
    private ?DateTimeInterface $raceDate;

    #[Vich\UploadableField(
        mapping: 'media_object',
        fileNameProperty: 'filePath'
    )]
    #[Assert\File(
        groups: ['media_object_create'],
        extensions: ['csv'],
        extensionsMessage: "File must be a valid 'csv' type."
    )]
    #[Assert\NotNull]
    public ?UploadedFile $file = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    public string $avgTimeForMediumDistance = '';

    #[ORM\Column(type: Types::STRING, nullable: true)]
    public string $avgTimeForLongDistance = '';

    /** @var Result[] $results All results for this race */
    #[ORM\OneToMany(mappedBy: "race", targetEntity: Result::class, cascade: ["persist", "remove"])]
    private iterable $results;

    public function __construct()
    {
        $this->results = new ArrayCollection();
    }

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
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getRaceDate(): ?DateTimeInterface
    {
        return $this->raceDate;
    }

    /**
     * @param DateTimeInterface|null $raceDate
     */
    public function setRaceDate(?DateTimeInterface $raceDate): void
    {
        $this->raceDate = $raceDate;
    }

    /**
     * @return Collection|Result[]
     */
    public function getResults(): Collection|array
    {
        return $this->results;
    }

    /**
     * @return string
     */
    public function getAvgTimeForMediumDistance(): string
    {
        return $this->avgTimeForMediumDistance;
    }

    /**
     * @param string $avgTimeForMediumDistance
     */
    public function setAvgTimeForMediumDistance(string $avgTimeForMediumDistance): void
    {
        $this->avgTimeForMediumDistance = $avgTimeForMediumDistance;
    }

    /**
     * @return string
     */
    public function getAvgTimeForLongDistance(): string
    {
        return $this->avgTimeForLongDistance;
    }

    /**
     * @param string $avgTimeForLongDistance
     */
    public function setAvgTimeForLongDistance(string $avgTimeForLongDistance): void
    {
        $this->avgTimeForLongDistance = $avgTimeForLongDistance;
    }

    public function addResult(Result $result): self
    {
        if (!$this->results->contains($result)) {
            $result->setRace($this);
        }

        return $this;
    }
}