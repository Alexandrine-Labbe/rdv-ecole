<?php

namespace App\Controller;

use App\Entity\Teacher;
use App\Form\Type\TeacherType;
use App\Repository\TeacherRepository;
use App\Service\MailerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AdminController extends AbstractController
{

    public function __construct(
        private readonly EntityManagerInterface      $entityManager,
        private readonly TeacherRepository           $teacherRepository,
        private readonly MailerService              $mailerService,
    )
    {
    }

    #[Route('/admin/teacher/manage', name: 'manage_teacher')]
    public function manageTeacher(): Response
    {
        $teachers = $this->teacherRepository->findBy([], ['last_name' => 'ASC']);

        return $this->render('teacher/admin/manage_teacher.html.twig', [
            'teachers' => $teachers,
        ]);
    }

    #[Route('/admin/teacher/delete/{id_teacher}', name: 'delete_teacher')]
    public function deleteTeacher(string $id_teacher): RedirectResponse
    {
        $teacher = $this->teacherRepository->find($id_teacher);
        $this->entityManager->remove($teacher);
        $this->entityManager->flush();
        $this->addFlash('success', 'Enseignant supprimé avec succès !');

        return $this->redirectToRoute('manage_teacher');
    }

    #[Route('/admin/teacher/invite', name: 'invite_teacher')]
    public function inviteTeacher(Request $request)
    {
        $teacher = new Teacher();

        $form = $this->createForm(TeacherType::class, $teacher);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $teacher = $form->getData();
            $teacher->setPasswordNeedReset(true);
            $teacher->setRoles(['ROLE_TEACHER']);

            $this->entityManager->persist($teacher);
            $this->entityManager->flush();

            $this->mailerService->sendMail(
                $teacher->getEmail(),
                'Bienvenue sur l\'application de prise de rendez-vous',
                $this->renderView('email/teacher_invitation.html.twig')
            );

            $this->addFlash('success', 'Enseignant invité avec succès !');

            return $this->redirectToRoute('manage_teacher');
        }

        return $this->render('teacher/admin/invite_teacher.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}