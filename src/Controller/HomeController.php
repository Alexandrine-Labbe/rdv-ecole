<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/')]
    public function index(): Response
    {
        $teachers = [
            [
                "name" => "Marc Pelletier",
                "picture" => "images/teacher/teacher1.jpeg"
            ],
            [
                "name" => "Antoine Rousseau",
                "picture" => "images/teacher/teacher4.jpeg"
            ],
            [
                "name" => "Isabelle Fournier",
                "picture" => "images/teacher/teacher5.jpeg"
            ],
            [
                "name" => "Julien Dubois",
                "picture" => "images/teacher/teacher2.jpeg"
            ],
            [
                "name" => "Sophie Martin",
                "picture" => "images/teacher/teacher6.jpeg"
            ],
            [
                "name" => "Claire Lefèvre",
                "picture" => "images/teacher/teacher7.jpeg"
            ],
            [
                "name" => "Nicolas Girard",
                "picture" => "images/teacher/teacher8.jpeg"
            ],
            [
                "name" => "Céline Moreau",
                "picture" => "images/teacher/teacher9.jpeg"
            ],
            [
                "name" => "Emile Laurent",
                "picture" => "images/teacher/teacher3.jpeg"
            ],
        ];

        return $this->render('index.html.twig', [
            'teachers' => $teachers
        ]);
    }
}
