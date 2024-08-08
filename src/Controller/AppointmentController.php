<?php

namespace App\Controller;

use App\Repository\AppointmentRepository;
use App\Repository\TeacherRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class AppointmentController extends AbstractController
{
    public function __construct(
        private TeacherRepository $teacherRepository,
        private AppointmentRepository $appointmentRepository,
        private EntityManagerInterface $entityManager
    )
    {
    }

    #[Route('/appointments/teacher/{id_teacher}', name: 'appointments_by_teacher')]
    public function selectAppoitnmentByTeacher(string $id_teacher)
    {
        $teacher = $this->teacherRepository->findOneBy(['id' => $id_teacher]);

        if (!$teacher->isAvailableForAppointment()) {
            $this->addFlash('error', 'Ce professeur n\'est pas disponible pour des rendez-vous.');
            return $this->redirectToRoute('home');
        }
        $appointments_available = $this->appointmentRepository->findBy(['teacher' => $id_teacher, 'child' => null], ['begin_at' => 'ASC']);
        $appointments_by_date = [];
        foreach ($appointments_available as $appointment) {
            $date = $appointment->getBeginAt()->format('Y-m-d');
            $appointments_by_date[$date][] = $appointment;
        }

        return $this->render('public/appointments_by_teacher.html.twig', [
            'teacher' => $teacher,
            'appointments_by_date' => $appointments_by_date,
        ]);
    }

    #[Route('/appointments/delete/{id_appointment}', name: 'delete_appointment')]
    #[IsGranted('ROLE_TEACHER')]
    public function deleteAppointment(string $id_appointment)
    {
        $appointment = $this->appointmentRepository->findOneBy(['id' => $id_appointment]);
        if (!$appointment || $appointment->getTeacher() !== $this->getUser()) {
            $this->addFlash('error', 'Ce rendez-vous n\'existe pas.');
            return $this->redirectToRoute('home');
        }

        $this->entityManager->remove($appointment);
        $this->entityManager->flush();

        $this->addFlash('success', 'Le rendez-vous a bien été supprimé.');
        return $this->redirectToRoute('teacher_appointments');
    }

    #[Route('/appointments/cancel/{id_appointment}', name: 'cancel_appointment')]
    #[IsGranted('ROLE_TEACHER')]
    public function cancelAppointment(string $id_appointment)
    {
        $appointment = $this->appointmentRepository->findOneBy(['id' => $id_appointment]);
        if (!$appointment || $appointment->getTeacher() !== $this->getUser()) {
            $this->addFlash('error', 'Ce rendez-vous n\'existe pas.');
            return $this->redirectToRoute('home');
        }

        $this->entityManager->flush();

        $this->addFlash('success', 'Le rendez-vous a bien été annulé.');
        return $this->redirectToRoute('child_appointments');

    }
}