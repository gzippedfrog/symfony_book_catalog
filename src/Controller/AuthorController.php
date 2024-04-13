<?php

namespace App\Controller;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Post;
use App\Form\AuthorType;
use App\Form\BookType;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/author')]
class AuthorController extends AbstractController
{
    #[Route('/', name: 'admin_author_index')]
    public function index(AuthorRepository $authorRepository): Response
    {
        return $this->render('admin/author/index.html.twig', [
            'authors' => $authorRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'admin_author_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $author = new Author();

        $form = $this->createForm(AuthorType::class, $author);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($author);
            $entityManager->flush();

            return $this->redirectToRoute('admin_author_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/author/new.html.twig', [
            'author' => $author,
            'form'   => $form,
        ]);
    }
//
//    #[Route('/{id<\d+>}/edit', name: 'admin_author_edit', methods: ['GET', 'POST'])]
//    public function edit(Request $request, Book $author, EntityManagerInterface $entityManager): Response
//    {
//        $form = $this->createForm(BookType::class, $author);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $entityManager->flush();
//
//            return $this->redirectToRoute('admin_author_index', [], Response::HTTP_SEE_OTHER);
//        }
//
//        return $this->render('admin/author/edit.html.twig', [
//            'author' => $author,
//            'form'   => $form,
//        ]);
//    }
//
//    #[Route('/{id<\d+>}/delete', name: 'admin_author_delete', methods: ['POST'])]
//    public function delete(Request $request, Book $author, EntityManagerInterface $entityManager): Response
//    {
//        $entityManager->remove($author);
//        $entityManager->flush();
//
//        return $this->redirectToRoute('admin_author_index', [], Response::HTTP_SEE_OTHER);
//    }
}
