<?php

namespace App\Entity;

use App\Repository\AppointmentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: AppointmentRepository::class)]
class Appointment
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE)]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE)]
    private ?\DateTimeImmutable $updated_at = null;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE)]
    private ?\DateTimeImmutable $begin_at = null;

    #[ORM\ManyToOne(inversedBy: 'appointments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Teacher $teacher = null;

    #[ORM\ManyToOne(inversedBy: 'appointments')]
    private ?Child $child = null;

    /**
     * @var Collection<int, Guardian>
     */
    #[ORM\ManyToMany(targetEntity: Guardian::class, inversedBy: 'appointments')]
    private Collection $guardians;

    public function __construct()
    {
        $this->guardians = new ArrayCollection();
        $this->created_at = new \DateTimeImmutable('now');
        $this->updated_at = new \DateTimeImmutable('now');
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeImmutable $updated_at): static
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getBeginAt(): ?\DateTimeImmutable
    {
        return $this->begin_at;
    }

    public function setBeginAt(\DateTimeImmutable $begin_at): static
    {
        $this->begin_at = $begin_at;

        return $this;
    }

    public function getTeacher(): ?Teacher
    {
        return $this->teacher;
    }

    public function setTeacher(?Teacher $teacher): static
    {
        $this->teacher = $teacher;

        return $this;
    }

    public function getChild(): ?Child
    {
        return $this->child;
    }

    public function setChild(?Child $child): static
    {
        $this->child = $child;

        return $this;
    }

    public function isAvailable(): bool
    {
        return $this->child === null;
    }

    /**
     * @return Collection<int, Guardian>
     */
    public function getGuardians(): Collection
    {
        return $this->guardians;
    }

    public function addGuardian(Guardian $guardian): static
    {
        if (!$this->guardians->contains($guardian)) {
            $this->guardians->add($guardian);
        }

        return $this;
    }

    public function removeGuardian(Guardian $guardian): static
    {
        $this->guardians->removeElement($guardian);

        return $this;
    }
}
