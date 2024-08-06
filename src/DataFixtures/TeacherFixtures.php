<?php

namespace App\DataFixtures;

use App\Entity\Teacher;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class TeacherFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher
    )
    {
    }

    public function load(ObjectManager $manager): void
    {
        $teachers = [
            [
                "first_name" => "Marc",
                "last_name" => "Pelletier",
                "picture" => "images/teacher/teacher1.jpeg",
                "grade" => "CP",
                "reference" => "teacher1",
            ],
            [
                "first_name" => "Antoine",
                "last_name" => "Rousseau",
                "picture" => "images/teacher/teacher4.jpeg",
                "grade" => "CP",
                "reference" => "teacher2",
            ],
            [
                "first_name" => "Isabelle",
                "last_name" => "Fournier",
                "picture" => "images/teacher/teacher5.jpeg",
                "grade" => "CE1",
                "reference" => "teacher3",
            ],
            [
                "first_name" => "Sophie",
                "last_name" => "Martin",
                "picture" => "images/teacher/teacher6.jpeg",
                "grade" => "CE1/CE2",
                "reference" => "teacher4",
            ],
            [
                "first_name" => "Claire",
                "last_name" => "Lefèvre",
                "picture" => "images/teacher/teacher7.jpeg",
                "grade" => "CE2",
                "reference" => "teacher5",
            ],
            [
                "first_name" => "Nicolas",
                "last_name" => "Girard",
                "picture" => "images/teacher/teacher8.jpeg",
                "grade" => "CM1",
                "reference" => "teacher6",
            ],
            [
                "first_name" => "Julien",
                "last_name" => "Dubois",
                "picture" => "images/teacher/teacher2.jpeg",
                "grade" => "CM1",
                "reference" => "teacher7",
            ],
            [
                "first_name" => "Céline",
                "last_name" => "Moreau",
                "picture" => "images/teacher/teacher9.jpeg",
                "grade" => "CM2",
                "reference" => "teacher8",
            ],
            [
                "first_name" => "Emile",
                "last_name" => "Laurent",
                "picture" => "images/teacher/teacher3.jpeg",
                "grade" => "CM2",
                "reference" => "teacher9",
            ],
        ];

        foreach ($teachers as $teacher) {
            $teacherEntity = new Teacher();
            $hashedPassword = $this->passwordHasher->hashPassword(
                $teacherEntity,
                'password'
            );

            $teacherEntity
                ->setFirstName($teacher['first_name'])
                ->setLastName($teacher['last_name'])
                ->setAvatar($teacher['picture'])
                ->setEmail($teacher['first_name'] . '.' . $teacher['last_name'] . '@ecole-primaire.fr')
                ->setPassword($hashedPassword)
                ->setRoles(['ROLE_TEACHER', 'ROLE_USER'])
                ->setGrade($teacher['grade']);
            $manager->persist($teacherEntity);
            $this->addReference($teacher['reference'], $teacherEntity);
        }

        $manager->flush();
    }
}