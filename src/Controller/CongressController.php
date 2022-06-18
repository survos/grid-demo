<?php

namespace App\Controller;

use App\Entity\Congress;
use App\Form\CongressType;
use App\Repository\CongressRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/congress')]
class CongressController extends AbstractController
{
    #[Route('/', name: 'app_congress_index', methods: ['GET'])]
    public function index(CongressRepository $congressRepository): Response
    {
        return $this->render('congress/index.html.twig', [
            'congresses' => $congressRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_congress_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CongressRepository $congressRepository): Response
    {
        $congress = new Congress();
        $form = $this->createForm(CongressType::class, $congress);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $congressRepository->add($congress, true);

            return $this->redirectToRoute('app_congress_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('congress/new.html.twig', [
            'congress' => $congress,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_congress_show', methods: ['GET'], options: ['expose' => true])]
    public function show(Congress $congress): Response
    {
        return $this->render('congress/show.html.twig', [
            'congress' => $congress,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_congress_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Congress $congress, CongressRepository $congressRepository): Response
    {
        $form = $this->createForm(CongressType::class, $congress);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $congressRepository->add($congress, true);

            return $this->redirectToRoute('app_congress_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('congress/edit.html.twig', [
            'congress' => $congress,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_congress_delete', methods: ['POST'])]
    public function delete(Request $request, Congress $congress, CongressRepository $congressRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$congress->getId(), $request->request->get('_token'))) {
            $congressRepository->remove($congress, true);
        }

        return $this->redirectToRoute('app_congress_index', [], Response::HTTP_SEE_OTHER);
    }
}
