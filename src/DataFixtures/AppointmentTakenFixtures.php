<?php

namespace App\DataFixtures;

use App\Entity\Child;
use App\Entity\Guardian;
use App\Repository\AppointmentRepository;
use App\Repository\ChildRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class AppointmentTakenFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private AppointmentRepository $appointmentRepository,
        private ChildRepository       $childRepository
    )
    {
    }

    public function getDependencies(): array
    {
        return [
            AppointmentFixtures::class,
            ChildrenFixtures::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        $children = $this->childRepository->findAll();
        $appointments = $this->appointmentRepository->findAll();

        foreach ($children as $child) {
            $appointment = $appointments[array_rand($appointments)];
            $appointment->setChild($child);
            $parents = $this->createGuardians($child);
            foreach ($parents as $parent) {
                $manager->persist($parent);
                $appointment->addGuardian($parent);
            }
            $manager->persist($appointment);
        }

        $manager->flush();
    }

    private function createGuardians(Child $child): array
    {
        $guardian1 = new Guardian();
        $first_name = $this->getRandomFirstName();
        $guardian1
            ->setFirstName($first_name)
            ->setLastName($child->getLastName())
            ->setEmail($first_name . '.' . $child->getLastName() . '@example.com');

        if (rand(0, 1)) {
            $guardian2 = new Guardian();
            $first_name = $this->getRandomFirstName();
            $guardian2
                ->setFirstName($first_name)
                ->setLastName($child->getLastName())
                ->setEmail($first_name . '.' . $child->getLastName() . '@example.com');
            return [$guardian1, $guardian2];
        }

        return [$guardian1];
    }

    private function getRandomFirstName()
    {
        $names = [
            'Alice',
            'Ben',
            'Chris',
            'Diana',
            'Ethan',
            'Fiona',
            'George',
            'Hannah',
            'Ian',
            'Jasmine',
            'Kevin',
            'Lily',
            'Michael',
            'Nina',
            'Oliver',
        ];

        return $names[array_rand($names)];
    }
}