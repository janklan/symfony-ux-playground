<?php

namespace App\Twig\Components;

use App\Dto\PostCreateDto;
use App\Dto\PostUpdateDto;
use App\Entity\Post;
use App\Form\PostType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\Attribute\PostHydrate;
use Symfony\UX\LiveComponent\Attribute\PreDehydrate;
use Symfony\UX\LiveComponent\Attribute\PreReRender;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\TwigComponent\Attribute\PostMount;
use Symfony\UX\TwigComponent\Attribute\PreMount;

#[AsLiveComponent('post_form')]
class PostFormComponent extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    #[LiveProp(fieldName: 'data')]
//    public ?PostUpdateDto $dto = null; // This would work, but I'm trying to build universal components where my DTO implement an interface.
    public PostCreateDto|PostUpdateDto|null $dto;

    // This is only called on first component render anyway - not on reloads.
    public function mount(array|PostCreateDto|PostUpdateDto $dto)
    {
        if (is_array($dto)) {
            dd($dto);
        }

        $this->dto = $dto;
    }

    #[PreMount]
    public function preMount(array $data): array
    {
        dump($data);
        return $data;
    }

    #[PostMount]
    public function postMount(array $data): array
    {
        dump($data);
        return $data;
    }

    #[PostHydrate]
    public function postHydrate() {
        dd(func_get_args());
    }

    #[PreDehydrate]
    public function preDehydrate() {
        dump(func_get_args());
    }

    #[PreReRender]
    public function preReRender() {
        dump(func_get_args());
    }



    protected function instantiateForm(): FormInterface
    {
        // we can extend AbstractController to get the normal shortcuts
        return $this->createForm(PostType::class, $this->dto, [
            'data_class' => $this->dto::class,
        ]);
    }
}
