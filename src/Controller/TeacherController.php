<?php

namespace App\Controller;

use App\Repository\AppointmentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class TeacherController extends AbstractController
{

    #[Route('/teacher/dashboard', name: 'teacher_dashboard')]
    public function dashboard(AppointmentRepository $appointmentRepository)
    {
//        $appointments = [
//            [
//                'datetime' => '2024-07-01T10:00:00+00:00',
//                'student' => 'Alice Johnson',
//                'parents' => [
//                    'Emma Johnson',
//                ],
//                'is_available' => false,
//            ],
//            [
//                'datetime' => '2024-07-01T11:00:00+00:00',
//                'student' => 'Ben Smith',
//                'parents' => [
//                    'Laura Smith',
//                    'David Smith',
//                ],
//                'is_available' => false,
//            ],
//            [
//                'datetime' => '2024-07-01T12:00:00+00:00',
//                'student' => 'Chris Brown',
//                'parents' => [
//                    'Sophie Brown',
//                    'Michael Brown',
//                ],
//                'is_available' => false,
//            ],
//            [
//                'datetime' => '2024-07-02T10:00:00+00:00',
//                'student' => null,
//                'parents' => [
//                ],
//                'is_available' => true,
//            ],
//            [
//                'datetime' => '2024-07-02T11:00:00+00:00',
//                'student' => 'Ethan Davis',
//                'parents' => [
//                    'Henry Davis',
//                ],
//                'is_available' => false,
//            ],
//            [
//                'datetime' => '2024-07-02T12:00:00+00:00',
//                'student' => 'Ian Walker',
//                'parents' => [
//                    'Mia Walker',
//                    'Elijah Walker',
//                ],
//                'is_available' => false,
//            ],
//            [
//                'datetime' => '2024-07-03T10:00:00+00:00',
//                'student' => 'George Clark',
//                'parents' => [
//                    'Ava Clark',
//                    'William Clark',
//                ],
//                'is_available' => false,
//            ],
//            [
//                'datetime' => '2024-07-03T11:00:00+00:00',
//                'student' => null,
//                'parents' => [
//                ],
//                'is_available' => true,
//            ],
//            [
//                'datetime' => '2024-07-03T12:00:00+00:00',
//                'student' => 'Ian Walker',
//                'parents' => [
//                    'Mia Walker',
//                ],
//                'is_available' => false,
//            ],
//            [
//                'datetime' => '2024-07-04T10:00:00+00:00',
//                'student' => null,
//                'parents' => [
//                ],
//                'is_available' => true,
//            ],
//            [
//                'datetime' => '2024-07-04T11:00:00+00:00',
//                'student' => 'Katie Wilson',
//                'parents' => [
//                    'Mason Wilson',
//                ],
//                'is_available' => false,
//            ],
//            [
//                'datetime' => '2024-07-04T12:00:00+00:00',
//                'student' => 'Katie Wilson',
//                'parents' => [
//                    'Samantha Wilson',
//                    'Mason Wilson',
//                ],
//                'is_available' => false,
//            ],
//        ];

        $appointments = $appointmentRepository->findBy(['teacher' => $this->getUser()], ['begin_at' => 'ASC']);

        return $this->render('teacher/dashboard.html.twig', [
            'appointments' => $appointments,
        ]);
    }
}