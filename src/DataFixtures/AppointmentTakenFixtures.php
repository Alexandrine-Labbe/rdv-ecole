<?php

namespace App\DataFixtures;

use App\Repository\AppointmentRepository;
use App\Repository\ChildRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class AppointmentTakenFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private AppointmentRepository $appointmentRepository,
        private ChildRepository $childRepository
    )
    {
    }

    public function getDependencies(): array
    {
        return [
            AppointmentFixtures::class,
            FamilyFixtures::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        $children = $this->childRepository->findAll();
        $appointments = $this->appointmentRepository->findAll();

        foreach ($children as $child) {
            $appointment = $appointments[array_rand($appointments)];
            $appointment->setChild($child);
            $parents = $child->getGuardians();
            foreach ($parents as $parent) {
                $appointment->addGuardian($parent);
            }
            $manager->persist($appointment);
        }

        $manager->flush();
    }
}