<?php

namespace App\DataFixtures;

use App\Entity\Appointment;
use App\Entity\Child;
use App\Entity\Guardian;
use App\Repository\AppointmentRepository;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class AppointmentTakenFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private AppointmentRepository $appointmentRepository
    )
    {
    }

    public function getDependencies()
    {
        return [
            AppointmentFixtures::class,
        ];
    }

    public function load(ObjectManager $manager)
    {

        $oneParentOneChild = [
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

        $oneParentMultipleChild = [
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


        $manager->flush();
    }

    private function getTeacher($ref)
    {
        return $this->getReference('teacher' . $ref);
    }

}