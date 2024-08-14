<?php

namespace App\Controller;

use App\Entity\Child;
use App\Entity\Guardian;
use App\Repository\AppointmentRepository;
use App\Repository\GuardianRepository;
use App\Repository\TeacherRepository;
use App\Service\MailerService;
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
        private EntityManagerInterface $entityManager,
        private MailerService $mailerService,
        private GuardianRepository $guardianRepository
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
        return $this->redirectToRoute('teacher_appointments');

    }

    #[Route('/appointments/take/{id_appointment}', name: 'take_appointment')]
    public function takeAppointment(string $id_appointment, Request $request): RedirectResponse|Response
    {
        $appointment = $this->appointmentRepository->findOneBy(['id' => $id_appointment]);
        if (!$appointment || $appointment->getChild()) {
            $this->addFlash('error', 'Ce rendez-vous n\'existe pas ou est déjà pris.');

            if ($appointment) {
                return $this->redirectToRoute('appointments_by_teacher', ['id_teacher' => $appointment->getTeacher()->getId()]);
            }
            return $this->redirectToRoute('home');
        }

        $form = $request->request->all();

        if (isset($form['form_submit'])) {
            $child = new Child();
            $child->setFirstName($form['child']['first_name']);
            $child->setLastName($form['child']['last_name']);
            $appointment->setChild($child);
            $appointment->setUpdatedAt(new DateTimeImmutable('now'));
            $this->entityManager->persist($child);

            foreach ($form['guardians'] as $guardian) {
                if (empty($guardian['first_name']) || empty($guardian['last_name'])) {
                   continue;
                }

                $guardian_entity = $this->guardianRepository->findOneBy(['email' => $guardian['email']]);
                if (!$guardian_entity) {
                    $guardian_entity = new Guardian();
                    $guardian_entity->setFirstName($guardian['first_name']);
                    $guardian_entity->setLastName($guardian['last_name']);
                    $guardian_entity->setEmail($guardian['email']);
                }

                $appointment->addGuardian($guardian_entity);
                $this->entityManager->persist($guardian_entity);

                if ($guardian['email']) {
                    $this->mailerService->sendMail(
                        $guardian['email'],
                        'Rendez-vous pris',
                        $this->renderView('email/appointment_validation.html.twig', [
                            'appointment' => $appointment,
                        ])
                    );
                }
            }

            $this->entityManager->flush();

            return $this->redirectToRoute('appointment_success', ['id_appointment' => $appointment->getId()]);
        }

        return $this->render('public/create_child.html.twig', [
            'appointment' => $appointment,
        ]);
    }

    #[Route('/appointments/success/{id_appointment}', name: 'appointment_success')]
    public function appointmentSuccess(string $id_appointment): Response
    {
        $appointment = $this->appointmentRepository->find(['id' => $id_appointment]);

        return $this->render('public/appointment_success.html.twig', [
            'appointment' => $appointment,
        ]);
    }

}