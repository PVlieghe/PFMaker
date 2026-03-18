<?php

namespace App\Controller;

use App\Entity\Section;
use App\Form\SectionType;
use App\Repository\SectionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


final class SectionController extends AbstractController
{
#[Route('/admin/section', name: 'app_admin_section_index')]
public function index(Request $request, SectionRepository $sectionRepository, EntityManagerInterface $em): Response
{
    $section = new Section();
    $formAddSection = $this->createForm(SectionType::class, $section);
    $formAddSection->handleRequest($request);

    if ($formAddSection->isSubmitted() && $formAddSection->isValid()) {
        $em->persist($section);
        $em->flush();

        return $this->redirectToRoute('app_admin_index');
    }

    return $this->render('admin/section/index.html.twig', [
        'sections' => $sectionRepository->findBy([], ['number' => 'ASC']),
        'form' => $form->createView(),
    ]);
}


    #[Route('/{id}', name: 'app_section_show', methods: ['GET'])]
    public function show(Section $section): Response
    {
        return $this->render('section/show.html.twig', [
            'section' => $section,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_section_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Section $section, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SectionType::class, $section);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_section_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('section/edit.html.twig', [
            'section' => $section,
            'form' => $form,
        ]);
    }

    #[Route('admin/section/{id}', name: 'app_section_delete', methods: ['POST'])]
    public function delete(Request $request, Section $section, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$section->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($section);
            $entityManager->flush();
        }

        if ($request->isXmlHttpRequest()) {
            return new Response(null, 204);
        }

        return $this->redirectToRoute('app_section_index');
    }
}
