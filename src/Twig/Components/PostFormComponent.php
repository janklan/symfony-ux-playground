<?php

namespace App\Twig\Components;

use App\Entity\Post;
use App\Form\PostType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('post_form')]
class PostFormComponent extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    /**
     * The initial data used to create the form.
     *
     * Needed so the same form can be re-created
     * when the component is re-rendered via Ajax.
     *
     * The `fieldName` option is needed in this situation because
     * the form renders fields with names like `name="post[title]"`.
     * We set `fieldName: ''` so that this live prop doesn't collide
     * with that data. The value - data - could be anything.
     */
    #[LiveProp(fieldName: 'data')]
    public ?Post $post = null;

    /**
     * Used to re-create the PostType form for re-rendering.
     */
    protected function instantiateForm(): FormInterface
    {
        dump($this->post);
        // we can extend AbstractController to get the normal shortcuts
        return $this->createForm(PostType::class, $this->post);
    }
}
