<?php

namespace App\DataFixtures;

use App\Entity\Appointment;
use App\Entity\Child;
use App\Entity\Guardian;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class AppointmentFixtures extends Fixture implements DependentFixtureInterface
{

    public function getDependencies()
    {
        return [
            TeacherFixtures::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {

        for ($i = 1; $i <= 9; $i++) {
            $start_date = new DateTimeImmutable('tomorrow 10:00');
            $end_date = new DateTimeImmutable('tomorrow 18:00');

            $appointment_duration = 30;
            while ($start_date < $end_date) {
                $this->createAppointment($manager, $i, $start_date);
                $start_date = $start_date->add(new \DateInterval('PT' . $appointment_duration . 'M'));
            }
        }

        $manager->flush();
    }

    private function getTeacher($ref)
    {
        return $this->getReference('teacher' . $ref);
    }

    private function createAppointment(ObjectManager $manager, int $i, DateTimeImmutable $begin_at)
    {
        $teacher = $this->getTeacher($i);

        $appointmentEntity = new Appointment();
        $appointmentEntity
            ->setTeacher($teacher)
            ->setCreatedAt(new DateTimeImmutable())
            ->setUpdatedAt(new DateTimeImmutable())
            ->setBeginAt($begin_at);

        $manager->persist($appointmentEntity);
    }
}