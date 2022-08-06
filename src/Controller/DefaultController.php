<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Author;
use App\Form\PostType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'default')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Fixtures - three countries to choose from
        if (!$entityManager->getRepository(Author::class)->count([])) {
            foreach (['Alice', 'Bob'] as $authorName) {
                $author = new Author($authorName);
                for ($i = 1; $i <= 3; $i++) {
                    $author->addPost($post = new Post('Post ' . $i));
                    if ($i > 1) {
                        $post->setRatingAllowed(true);
                        if ($i > 2) {
                            $post->setRatingValue($i);
                        }
                    }
                }

                $entityManager->persist($author);
            }
            $entityManager->flush();
        }

        return $this->render('default/index.html.twig', [
            'authors' => $entityManager->getRepository(Author::class)->findAll(),
        ]);
    }

    #[Route('/admin/post/{id}/edit', name: 'edit')]
    public function edit(Request $request, Post $post, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('default');
        }

        return $this->renderForm('default/edit.html.twig', [
            'post' => $post,
            'form' => $form,
        ]);
    }
}
