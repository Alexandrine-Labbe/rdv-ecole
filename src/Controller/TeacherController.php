<?php

namespace App\Controller;

use App\Repository\AppointmentRepository;
use App\Repository\ChildRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class TeacherController extends AbstractController
{

    #[Route('/teacher/dashboard', name: 'teacher_dashboard')]
    public function dashboard(AppointmentRepository $appointmentRepository)
    {
        return $this->render('teacher/dashboard.html.twig', [
        ]);
    }

    #[Route('/teacher/appointments', name: 'teacher_appointments')]
    public function appointments(AppointmentRepository $appointmentRepository)
    {
        $appointments = $appointmentRepository->findBy(['teacher' => $this->getUser()], ['begin_at' => 'ASC']);

        return $this->render('teacher/appointments.html.twig', [
            'appointments' => $appointments,
        ]);
    }

    #[Route('/teacher/students', name: 'teacher_students')]
    public function students(ChildRepository $childRepository)
    {
        $students = $childRepository->findByTeacher($this->getUser()->getId());

        return $this->render('teacher/students.html.twig', [
            'students' => $students,
        ]);
    }
}