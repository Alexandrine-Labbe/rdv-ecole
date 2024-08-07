<?php

namespace App\Controller;

use App\Repository\AppointmentRepository;
use App\Repository\ChildRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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

    #[Route('/teacher/account', name: 'teacher_account')]
    public function account()
    {
        $teacher = $this->getUser();

        $form = $this->createFormBuilder($teacher)
            ->add('email', EmailType::class)
            ->add('firstname', TextType::class)
            ->add('lastname', TextType::class)
            ->add('password', PasswordType::class)
            ->add('grade', TextType::class)
            ->add('avatar', PasswordType::class)
            ->add('is_available_for_appointment', CheckboxType::class)
            ->add('save', SubmitType::class)
            ->getForm();

        return $this->render('teacher/account.html.twig', [
            'form' => $form,
        ]);
    }
}