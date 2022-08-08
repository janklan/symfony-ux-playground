<?php

namespace App\Controller;

use App\Dto\PostCreateDto;
use App\Dto\PostUpdateDto;
use App\Entity\Post;
use App\Entity\Author;
use App\Entity\Tag;
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
            $tags = [];
            for ($i = 1; $i <= 3; $i++) {
                $tags[] = $tag = new Tag('tag'.$i);
                $entityManager->persist($tag);
            }

            foreach (['Alice', 'Bob'] as $authorName) {
                $author = new Author($authorName);
                for ($i = 1; $i <= 3; $i++) {

                    $postDto = new PostCreateDto();
                    $postDto->name = 'Post '.$i;
                    $postDto->author = $author;
                    if ($i > 1) {
                        $postDto->ratingAllowed = true;
                        if ($i > 2) {
                            $postDto->ratingValue = $i;
                        }
                    }

                    for ($j = 1; $j < $i; $j++) {
                        $postDto->tags->add($tags[$i-1]);
                    }

                    $post = Post::create($postDto);
                    $author->addPost($post);

                    foreach ($postDto->tags as $tag) {
                        $tag->addPost($post);
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

    #[Route('/new', name: 'new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $dto = new PostCreateDto();
        $form = $this->createForm(PostType::class, $dto, [
            'data_class' => $dto::class
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            assert($data instanceof PostCreateDto);

            $entityManager->persist(Post::create($data));
            $entityManager->flush();

            return $this->redirectToRoute('default');
        }

        return $this->renderForm('default/new.html.twig', [
            'form' => $form,
            'dto' => $dto,
        ]);
    }

    #[Route('/edit/{post}', name: 'edit')]
    public function edit(Request $request, Post $post, EntityManagerInterface $entityManager): Response
    {
        $dto = PostUpdateDto::createFrom($post);

        $form = $this->createForm(PostType::class, $dto, [
            'data_class' => $dto::class,
            'action' => $this->generateUrl('edit', ['post' => $post->getId(), 'standard' => $request->get('standard')])
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();
            assert($data instanceof PostUpdateDto);
            $post->updateWith($dto);

            $entityManager->flush();

            $this->addFlash('success', 'The form was submitted, changes were saved. Timestamp: '.date('r'));

            return $this->redirectToRoute('edit', ['post' => $post->getId(), 'standard' => (bool)$request->get('standard')]);
        }

        return $this->renderForm('default/edit.html.twig', [
            'post' => $post,
            'form' => $form,
            'dto' => $dto,
        ]);
    }
}
