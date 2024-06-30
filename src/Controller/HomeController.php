<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\TeacherRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/')]
    public function index(TeacherRepository $teacherRepository): Response
    {
        $teachers = [
            [
                "name" => "Marc Pelletier",
                "picture" => "images/teacher/teacher1.jpeg",
                "grade" => "CP",
            ],
            [
                "name" => "Antoine Rousseau",
                "picture" => "images/teacher/teacher4.jpeg",
                "grade" => "CP",
            ],
            [
                "name" => "Isabelle Fournier",
                "picture" => "images/teacher/teacher5.jpeg",
                "grade" => "CE1",
            ],
            [
                "name" => "Sophie Martin",
                "picture" => "images/teacher/teacher6.jpeg",
                "grade" => "CE1/CE2",
            ],
            [
                "name" => "Claire Lefèvre",
                "picture" => "images/teacher/teacher7.jpeg",
                "grade" => "CE2",
            ],
            [
                "name" => "Nicolas Girard",
                "picture" => "images/teacher/teacher8.jpeg",
                "grade" => "CM1",
            ],
            [
                "name" => "Julien Dubois",
                "picture" => "images/teacher/teacher2.jpeg",
                "grade" => "CM1",
            ],
            [
                "name" => "Céline Moreau",
                "picture" => "images/teacher/teacher9.jpeg",
                "grade" => "CM2",
            ],
            [
                "name" => "Emile Laurent",
                "picture" => "images/teacher/teacher3.jpeg",
                "grade" => "CM2",
            ],
        ];

        return $this->render('index.html.twig', [
            'teachers' => $teacherRepository->findBy([], ['first_name' => 'ASC']),
        ]);
    }
}
