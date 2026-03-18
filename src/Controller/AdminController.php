<?php

namespace App\Controller;

use App\Entity\Section;
use App\Form\SectionType;
use App\Repository\SectionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class AdminController extends AbstractController
{
    
    #[Route('/admin', name: 'app_admin', methods: ['GET', 'POST'])]
    public function index(
        SectionRepository $sectionRepository,
        EntityManagerInterface $em,
        Request $request
    ): Response {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_login');
        }

        $section = new Section();
        $form = $this->createForm(SectionType::class, $section);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($section);
            $em->flush();

            // 👉 Requête AJAX
            if ($request->isXmlHttpRequest()) {
                return $this->render('admin/section/_row.html.twig', [
                    'section' => $section,
                ]);
            }

            return $this->redirectToRoute('app_admin');
        }

        return $this->render('admin/index.html.twig', [
            'sections' => $sectionRepository->findBy([], ['number' => 'ASC']),
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/user', name: 'app_admin_user')]
    public function user(): Response
    {
        // Redirection si l'utilisateur n'est pas admin
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_login');
        }
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/admin/para', name: 'app_admin_para')]
    public function para(): Response
    {
        // Redirection si l'utilisateur n'est pas admin
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_login');
        }
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
}
