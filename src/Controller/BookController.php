<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/book', 'admin_book_')]
class BookController extends AbstractController
{
    function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    #[Route('/', name: 'index')]
    public function index(BookRepository $bookRepository): Response
    {
        return $this->render('admin/book/index.html.twig', [
            'books' => $bookRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, FileUploader $fileUploader): Response
    {
        $book = new Book();

        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $coverImage */
            $coverImage = $form->get('coverImage')->getData();

            if ($coverImage) {
                try {
                    $coverFilename = $fileUploader->upload($coverImage);
                    $book->setCoverFilename($coverFilename);
                } catch (FileException) {
                    $form->get('coverImage')->addError(new FormError('There was an error uploading image file.'));

                    return $this->render('admin/book/new.html.twig', [
                        'form' => $form,
                    ]);
                }
            }

            $this->entityManager->persist($book);
            $this->entityManager->flush();

            return $this->redirectToRoute('admin_book_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/book/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id<\d+>}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Book $book, FileUploader $fileUploader): Response
    {
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $coverImage */
            $coverImage = $form->get('coverImage')->getData();

            if ($coverImage) {
                try {
                    $coverFilename = $fileUploader->upload($coverImage);
                    $book->setCoverFilename($coverFilename);
                } catch (FileException) {
                    $form->get('coverImage')->addError(new FormError('There was an error uploading image file.'));

                    return $this->render('admin/book/edit.html.twig', [
                        'book' => $book,
                        'form' => $form,
                    ]);
                }
            }

            $this->entityManager->persist($book);
            $this->entityManager->flush();

            return $this->redirectToRoute('admin_book_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/book/edit.html.twig', [
            'book' => $book,
            'form' => $form,
        ]);
    }

    #[Route('/{id<\d+>}/delete', name: 'delete', methods: ['POST'])]
    public function delete(Book $book): Response
    {
        $this->entityManager->remove($book);
        $this->entityManager->flush();

        return $this->redirectToRoute('admin_book_index', [], Response::HTTP_SEE_OTHER);
    }
}
