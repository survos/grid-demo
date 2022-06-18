<?php

namespace App\Controller;

use App\Repository\CongressRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Intl\Countries;
use Symfony\Component\Intl\Languages;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
    #[Route('/', name: 'app_homepage')]
    public function index(CongressRepository $congressRepository): Response
    {
        return $this->render('app/index.html.twig', [
            'officials' => $congressRepository->findAll(),
            'controller_name' => 'AppController',
        ]);
    }
}
