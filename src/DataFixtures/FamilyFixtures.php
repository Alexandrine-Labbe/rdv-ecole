<?php

namespace App\DataFixtures;

use App\Entity\Child;
use App\Entity\Guardian;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\Uuid;

class FamilyFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $oneParentOneChild = $this->getOneParentOneChild();
        foreach ($oneParentOneChild as $parentChild) {
            $parent = $this->createParent($parentChild['parent']);
            $manager->persist($parent);

            $child = $this->createChild($parentChild['student']);
            $child->addGuardian($parent);
            $manager->persist($child);
        }

        $oneParentMultipleChildren = $this->getOneParentMultipleChildren();
        foreach ($oneParentMultipleChildren as $parentChildren) {
            $parent = $this->createParent($parentChildren['parent']);
            $manager->persist($parent);

            foreach ($parentChildren['children'] as $child) {
                $childEntity = $this->createChild($child);
                $childEntity->addGuardian($parent);
                $manager->persist($childEntity);
            }
        }

        $multipleParentsOneChild = $this->getMultipleParentsOneChild();
        foreach ($multipleParentsOneChild as $parentsChild) {
            $child = $this->createChild($parentsChild['child']);
            $manager->persist($child);

            foreach ($parentsChild['parents'] as $parent) {
                $parentEntity = $this->createParent($parent);
                $child->addGuardian($parentEntity);
                $manager->persist($parentEntity);
            }
        }

        $multipleParentsMultipleChildren = $this->getMultipleParentsMultipleChildren();
        foreach ($multipleParentsMultipleChildren as $parentsChildren) {
            foreach ($parentsChildren['children'] as $child) {
                $childEntity = $this->createChild($child);
                $manager->persist($childEntity);
            }

            foreach ($parentsChildren['parents'] as $parent) {
                $parentEntity = $this->createParent($parent);
                $manager->persist($parentEntity);
            }
        }


        $manager->flush();
    }

    private function getOneParentOneChild(): array
    {
        return [
            [
                'student' => [
                    'first_name' => 'Alice',
                    'last_name' => 'Johnson',
                ],
                'parent' => [
                    'first_name' => 'Emma',
                    'last_name' => 'Johnson',
                ],
            ],
            [
                'student' => [
                    'first_name' => 'Ben',
                    'last_name' => 'Smith',
                ],
                'parent' => [
                    'first_name' => 'Laura',
                    'last_name' => 'Smith',
                ],
            ],
            [
                'student' => [
                    'first_name' => 'Chris',
                    'last_name' => 'Brown',
                ],
                'parent' => [
                    'first_name' => 'Sophie',
                    'last_name' => 'Brown',
                ],
            ],
            [
                'student' => [
                    'first_name' => 'Diana',
                    'last_name' => 'White',
                ],
                'parent' => [
                    'first_name' => 'Olivia',
                    'last_name' => 'White',
                ],
            ],
            [
                'student' => [
                    'first_name' => 'Ethan',
                    'last_name' => 'Davis',
                ],
                'parent' => [
                    'first_name' => 'Grace',
                    'last_name' => 'Davis',
                ],
            ],
        ];
    }

    private function getOneParentMultipleChildren(): array
    {
        return [
            [
                'parent' => [
                    'first_name' => 'Emma',
                    'last_name' => 'Johnson',
                ],
                'children' => [
                    [
                        'first_name' => 'Alice',
                        'last_name' => 'Johnson',
                    ],
                    [
                        'first_name' => 'Tom',
                        'last_name' => 'Johnson',
                    ]
                ]
            ],
            [
                'parent' => [
                    'first_name' => 'Laura',
                    'last_name' => 'Smith',
                ],
                'children' => [
                    [
                        'first_name' => 'Ben',
                        'last_name' => 'Smith',
                    ],
                    [
                        'first_name' => 'Sophie',
                        'last_name' => 'Smith',
                    ]
                ]
            ],
            [
                'parent' => [
                    'first_name' => 'Michael',
                    'last_name' => 'Brown',
                ],
                'children' => [
                    [
                        'first_name' => 'Chris',
                        'last_name' => 'Brown',
                    ],
                    [
                        'first_name' => 'Lucy',
                        'last_name' => 'Brown',
                    ],
                    [
                        'first_name' => 'Jack',
                        'last_name' => 'Brown',
                    ]
                ]
            ],
            [
                'parent' => [
                    'first_name' => 'Olivia',
                    'last_name' => 'White',
                ],
                'children' => [
                    [
                        'first_name' => 'Diana',
                        'last_name' => 'White',
                    ],
                    [
                        'first_name' => 'Mia',
                        'last_name' => 'White',
                    ]
                ]
            ],
            [
                'parent' => [
                    'first_name' => 'Grace',
                    'last_name' => 'Davis',
                ],
                'children' => [
                    [
                        'first_name' => 'Ethan',
                        'last_name' => 'Davis',
                    ],
                    [
                        'first_name' => 'James',
                        'last_name' => 'Davis',
                    ],
                    [
                        'first_name' => 'Ella',
                        'last_name' => 'Davis',
                    ]
                ]
            ],
        ];
    }

    private function getMultipleParentsOneChild(): array
    {
        return [
            [
                'child' => [
                    'first_name' => 'Alice',
                    'last_name' => 'Johnson',
                ],
                'parents' => [
                    [
                        'first_name' => 'Emma',
                        'last_name' => 'Johnson',
                    ],
                    [
                        'first_name' => 'Robert',
                        'last_name' => 'Johnson',
                    ]
                ]
            ],
            [
                'child' => [
                    'first_name' => 'Ben',
                    'last_name' => 'Smith',
                ],
                'parents' => [
                    [
                        'first_name' => 'Laura',
                        'last_name' => 'Smith',
                    ],
                    [
                        'first_name' => 'David',
                        'last_name' => 'Smith',
                    ]
                ]
            ],
            [
                'child' => [
                    'first_name' => 'Chris',
                    'last_name' => 'Brown',
                ],
                'parents' => [
                    [
                        'first_name' => 'Sophie',
                        'last_name' => 'Brown',
                    ],
                    [
                        'first_name' => 'Michael',
                        'last_name' => 'Brown',
                    ]
                ]
            ],
            [
                'child' => [
                    'first_name' => 'Diana',
                    'last_name' => 'White',
                ],
                'parents' => [
                    [
                        'first_name' => 'Olivia',
                        'last_name' => 'White',
                    ],
                    [
                        'first_name' => 'James',
                        'last_name' => 'White',
                    ]
                ]
            ],
            [
                'child' => [
                    'first_name' => 'Ethan',
                    'last_name' => 'Davis',
                ],
                'parents' => [
                    [
                        'first_name' => 'Grace',
                        'last_name' => 'Davis',
                    ],
                    [
                        'first_name' => 'Henry',
                        'last_name' => 'Davis',
                    ]
                ]
            ],
        ];
    }

    private function getMultipleParentsMultipleChildren(): array
    {
        return [
            [
                'parents' => [
                    [
                        'first_name' => 'Emma',
                        'last_name' => 'Johnson',
                    ],
                    [
                        'first_name' => 'Robert',
                        'last_name' => 'Johnson',
                    ]
                ],
                'children' => [
                    [
                        'first_name' => 'Alice',
                        'last_name' => 'Johnson',
                    ],
                    [
                        'first_name' => 'Tom',
                        'last_name' => 'Johnson',
                    ]
                ]
            ],
            [
                'parents' => [
                    [
                        'first_name' => 'Laura',
                        'last_name' => 'Smith',
                    ],
                    [
                        'first_name' => 'David',
                        'last_name' => 'Smith',
                    ]
                ],
                'children' => [
                    [
                        'first_name' => 'Ben',
                        'last_name' => 'Smith',
                    ],
                    [
                        'first_name' => 'Sophie',
                        'last_name' => 'Smith',
                    ]
                ]
            ],
            [
                'parents' => [
                    [
                        'first_name' => 'Sophie',
                        'last_name' => 'Brown',
                    ],
                    [
                        'first_name' => 'Michael',
                        'last_name' => 'Brown',
                    ]
                ],
                'children' => [
                    [
                        'first_name' => 'Chris',
                        'last_name' => 'Brown',
                    ],
                    [
                        'first_name' => 'Lucy',
                        'last_name' => 'Brown',
                    ],
                    [
                        'first_name' => 'Jack',
                        'last_name' => 'Brown',
                    ]
                ]
            ],
            [
                'parents' => [
                    [
                        'first_name' => 'Olivia',
                        'last_name' => 'White',
                    ],
                    [
                        'first_name' => 'James',
                        'last_name' => 'White',
                    ]
                ],
                'children' => [
                    [
                        'first_name' => 'Diana',
                        'last_name' => 'White',
                    ],
                    [
                        'first_name' => 'Mia',
                        'last_name' => 'White',
                    ]
                ]
            ],
            [
                'parents' => [
                    [
                        'first_name' => 'Grace',
                        'last_name' => 'Davis',
                    ],
                    [
                        'first_name' => 'Henry',
                        'last_name' => 'Davis',
                    ]
                ],
                'children' => [
                    [
                        'first_name' => 'Ethan',
                        'last_name' => 'Davis',
                    ],
                    [
                        'first_name' => 'James',
                        'last_name' => 'Davis',
                    ],
                    [
                        'first_name' => 'Ella',
                        'last_name' => 'Davis',
                    ]
                ]
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

    private function createParent(array $parent): Guardian
    {
        $parentEntity = new Guardian();
        $parentEntity
            ->setFirstName($parent['first_name'])
            ->setLastName($parent['last_name'])
            ->setEmail(Uuid::v4()->toRfc4122() . '@ecole-primaire.fr');

        return $parentEntity;
    }
}