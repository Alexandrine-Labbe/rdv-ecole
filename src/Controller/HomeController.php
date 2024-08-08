<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\TeacherRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(TeacherRepository $teacherRepository): Response
    {

        return $this->render('public/index.html.twig', [
            'teachers' => $teacherRepository->findBy(['is_available_for_appointment' => true], ['first_name' => 'ASC']),
        ]);
    }
}
