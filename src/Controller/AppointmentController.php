<?php

namespace App\Controller;

use App\Entity\Child;
use App\Repository\AppointmentRepository;
use App\Repository\TeacherRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
    public function selectAppoitnmentByTeacher(string $id_teacher): RedirectResponse|Response
    {
        $teacher = $this->teacherRepository->findOneBy(['id' => $id_teacher]);

        if (!$teacher->isAvailableForAppointment()) {
            $this->addFlash('error', 'Ce professeur n\'est pas disponible pour des rendez-vous.');
            return $this->redirectToRoute('home');
        }
        $appointments_available = $this->appointmentRepository->findBy(['teacher' => $id_teacher, 'child' => null], ['begin_at' => 'ASC']);
        $appointments_by_date = [];
        foreach ($appointments_available as $appointment) {
            if ($appointment->getBeginAt() < new DateTimeImmutable('now')) {
                continue;
            }
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
    public function deleteAppointment(string $id_appointment): RedirectResponse
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
    public function cancelAppointment(string $id_appointment): RedirectResponse
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

    #[Route('/appointments/take/{id_appointment}', name: 'take_appointment')]
    public function takeAppointment(string $id_appointment, Request $request, EntityManagerInterface $entityManager): RedirectResponse|Response
    {
        $appointment = $this->appointmentRepository->findOneBy(['id' => $id_appointment]);
        if (!$appointment || $appointment->getChild()) {
            $this->addFlash('error', 'Ce rendez-vous n\'existe pas ou est déjà pris.');

            if ($appointment) {
                return $this->redirectToRoute('appointments_by_teacher', ['id_teacher' => $appointment->getTeacher()->getId()]);
            }
            return $this->redirectToRoute('home');
        }

        $child = new Child();
        $form = $this->createFormBuilder($child)
            ->add('firstname', TextType::class, ['label' => 'Prénom de l\'enfant'])
            ->add('lastname', TextType::class, ['label' => 'Nom de l\'enfant'])
            ->add('save', SubmitType::class, ['label' => 'Enregistrer'])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $child = $form->getData();

            $appointment->setChild($child);
            $appointment->setUpdatedAt(new DateTimeImmutable('now'));

            $entityManager->persist($child);
            $entityManager->flush();

            $this->addFlash('success', 'Votre rendez-vous a été validé.');

            return $this->redirectToRoute('create_appointment_guardian', ['id_appointment' => $appointment->getId()]);
        }

        return $this->render('public/create_child.html.twig', [
            'form' => $form,
            'appointment' => $appointment,
        ]);
    }

    #[Route('/appointments/guardian/{id_appointment}', name: 'create_appointment_guardian')]
    public function createAppointmentGuardian(string $id_appointment)
    {

        return $this->render('public/create_guardian.html.twig');
    }
}