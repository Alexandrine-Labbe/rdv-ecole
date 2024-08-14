<?php

namespace App\DataFixtures;

use App\Entity\Child;
use App\Entity\Guardian;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\Uuid;

class ChildrenFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $children = $this->getChildren();
        foreach ($children as $child) {
            $child_entity = $this->createChild($child);
            $manager->persist($child_entity);
        }

        $manager->flush();
    }

    private function getChildren(): array
    {
        return [
            [
                'first_name' => 'Alice',
                'last_name' => 'Johnson',
            ],
            [
                'first_name' => 'Ben',
                'last_name' => 'Smith',
            ],
            [
                'first_name' => 'Chris',
                'last_name' => 'Brown',
            ],
            [
                'first_name' => 'Diana',
                'last_name' => 'White',
            ],
            [
                'first_name' => 'Ethan',
                'last_name' => 'Davis',
            ],
            [
                'first_name' => 'Fiona',
                'last_name' => 'Garcia',
            ],
            [
                'first_name' => 'George',
                'last_name' => 'Martinez',
            ],
            [
                'first_name' => 'Hannah',
                'last_name' => 'Rodriguez',
            ],
            [
                'first_name' => 'Ian',
                'last_name' => 'Wilson',
            ],
            [
                'first_name' => 'Jasmine',
                'last_name' => 'Lopez',
            ],
            [
                'first_name' => 'Kevin',
                'last_name' => 'Gonzalez',
            ],
            [
                'first_name' => 'Lily',
                'last_name' => 'Anderson',
            ],
            [
                'first_name' => 'Michael',
                'last_name' => 'Thomas',
            ],
            [
                'first_name' => 'Nina',
                'last_name' => 'Harris',
            ],
            [
                'first_name' => 'Oliver',
                'last_name' => 'Clark',
            ],
        ];
    }

    private function createChild(array $child): Child
    {
        $childEntity = new Child();
        $childEntity
            ->setFirstName($child['first_name'])
            ->setLastName($child['last_name']);

        return $childEntity;
    }

}