<?php

namespace App\Controller;

use App\Entity\Appointment;
use App\Form\Type\TeacherType;
use App\Repository\AppointmentRepository;
use App\Repository\ChildRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class TeacherController extends AbstractController
{

    #[Route('/teacher/dashboard', name: 'teacher_dashboard')]
    public function dashboard()
    {

        return $this->render('teacher/dashboard.html.twig', [
        ]);
    }

    #[Route('/teacher/appointments', name: 'teacher_appointments')]
    public function appointments(AppointmentRepository $appointmentRepository)
    {
        $appointments = $appointmentRepository->findBy(['teacher' => $this->getUser()], ['begin_at' => 'ASC']);

        $appointments_by_date = [];
        foreach ($appointments as $appointment) {
            $date = $appointment->getBeginAt()->format('Y-m-d');
            $appointments_by_date[$date][] = $appointment;
        }

        return $this->render('teacher/appointments.html.twig', [
            'appointments_by_date' => $appointments_by_date,
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
    public function account(Request $request, EntityManagerInterface $entityManager)
    {
        $teacher = $this->getUser();

        $form = $this->createForm(TeacherType::class, $teacher);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $teacher = $form->getData();

            $entityManager->persist($teacher);
            $entityManager->flush();

            $this->addFlash('success', 'Vos informations ont été mises à jour.');

            return $this->redirectToRoute('teacher_account');
        }

        return $this->render('teacher/account.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/teacher/account/password', name: 'teacher_account_password')]
    #[isGranted('IS_AUTHENTICATED_FULLY')]
    public function updatePassword(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher)
    {
        $security = [
            'password' => '',
            'password_repeat' => '',
        ];

        $form = $this->createFormBuilder($security)
            ->add('password', PasswordType::class, [
                'label' => 'Nouveau mot de passe',
            ])
            ->add('password_repeat', PasswordType::class, [
                'label' => 'Confirmer le mot de passe',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Enregistrer',
            ])
            ->getForm();

        $form->handleRequest($request);
        $security = $form->getData();

        if ($security['password'] !== $security['password_repeat']) {
            $form->addError(new FormError('Les mots de passe ne correspondent pas.'));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $teacher = $this->getUser();

            $hashed_password = $passwordHasher->hashPassword($this->getUser(), $security['password']);
            $teacher->setPassword($hashed_password);
            $entityManager->persist($teacher);
            $entityManager->flush();

            $this->addFlash('success', 'Votre mot de passe a été mis à jour.');

            return $this->redirectToRoute('teacher_account_password');
        }

        return $this->render('teacher/account_password.html.twig', [
            'form' => $form->createView(),
        ]);

    }

    #[Route('/teacher/appointments/create', name: 'teacher_appointments_create')]
    public function createAppointments(Request $request, EntityManagerInterface $entityManager)
    {
        $availability = [
            'date_begin' => new \DateTimeImmutable('now'),
            'date_end' => new \DateTimeImmutable('now'),
            'duration' => null,
        ];

        $form_multiple = $this->createFormBuilder($availability)
            ->add('date_begin', DateTimeType::class, [
                'label' => 'Date de début',
                'input' => 'datetime_immutable',
            ])
            ->add('date_end', DateTimeType::class, [
                'label' => 'Date de fin',
                'input' => 'datetime_immutable',
            ])
            ->add('duration', ChoiceType::class, [
                'label' => 'Durée du rendez-vous',
                'choices' => [
                    '15 minutes' => 15,
                    '20 minutes' => 20,
                    '30 minutes' => 30,
                    '45 minutes' => 45,
                    '60 minutes' => 60  ,
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Enregistrer',
            ])
            ->getForm();

        $form_multiple->handleRequest($request);
        $availability = $form_multiple->getData();

        if ($availability['date_begin'] > $availability['date_end']) {
            $form_multiple->addError(new FormError('La date de début doit être inférieur à la date de fin.'));
        }

        if ($form_multiple->isSubmitted() && $form_multiple->isValid()) {
            $duration = new \DateInterval("PT$availability[duration]M");

            while ($availability['date_begin'] < $availability['date_end']) {
                $appointment_entity = new Appointment();
                $appointment_entity->setBeginAt($availability['date_begin']);
                $appointment_entity->setTeacher($this->getUser());
                $entityManager->persist($appointment_entity);

                $availability['date_begin'] = $availability['date_begin']->add($duration);
            }

            $entityManager->flush();

            $this->addFlash('success', 'Vous avez bien créé des rendez-vous.');

            return $this->redirectToRoute('teacher_appointments');
        }


        $appointment = [
            'date_begin' => new \DateTimeImmutable('now'),
        ];

        $form_one = $this->createFormBuilder($appointment)
            ->add('date_begin', DateTimeType::class, [
                'label' => 'Date de début',
                'input' => 'datetime_immutable',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Enregistrer',
            ])
            ->getForm();

        $form_one->handleRequest($request);
        $appointment = $form_multiple->getData();

        if ($form_one->isSubmitted() && $form_one->isValid()) {
            $appointment_entity = new Appointment();
            $appointment_entity->setBeginAt($appointment['date_begin']);
            $appointment_entity->setTeacher($this->getUser());
            $entityManager->persist($appointment_entity);

            $entityManager->flush();

            $this->addFlash('success', 'Vous avez bien créé le rendez-vous.');

            return $this->redirectToRoute('teacher_appointments');
        }

        return $this->render('teacher/appointments_form.html.twig', [
            'form_multiple' => $form_multiple->createView(),
            'form_one' => $form_one->createView(),
        ]);
    }
}