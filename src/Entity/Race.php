<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/** A Race model */
#[ApiResource]
#[Get]
#[Post]
#[GetCollection]
#[Patch]
#[Delete]
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
}