<?php

namespace App\Form;

use App\Entity\Author;
use App\Entity\Post;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', options: ['required' => true])
            ->add('author', EntityType::class, options: [
                /**
                 * The UX Autocompleter works fine on first load, but on "live reload" it reverts back to a standard select.
                 *
                 * The generated row markup on first load (sorry for the long line): <div><label for="post_author-ts-control" class="required" id="post_author-ts-label">Author</label><select id="post_author" name="post[author]" data-controller="symfony--ux-autocomplete--autocomplete" data-symfony--ux-autocomplete--autocomplete-no-results-found-text-value="No results found" data-symfony--ux-autocomplete--autocomplete-no-more-results-text-value="No more results" tabindex="-1" class="tomselected ts-hidden-accessible"><option value="2">Bob</option><option value="1" selected="selected">Alice</option></select><div class="ts-wrapper single plugin-clear_button input-hidden full has-items"><div class="ts-control"><div data-value="1" class="item" data-ts-item="">Alice</div><input type="select-one" autocomplete="off" size="1" tabindex="0" role="combobox" aria-haspopup="listbox" aria-expanded="false" aria-controls="post_author-ts-dropdown" id="post_author-ts-control" aria-labelledby="post_author-ts-label"><div class="clear-button" title="">×</div></div><div class="ts-dropdown single plugin-clear_button" style="display: none;"><div role="listbox" tabindex="-1" class="ts-dropdown-content" id="post_author-ts-dropdown" aria-labelledby="post_author-ts-label"></div></div></div></div>
                 * Markup after live reload: <div><label for="post_author" class="required">Author</label><select id="post_author" name="post[author]" data-controller="symfony--ux-autocomplete--autocomplete" data-symfony--ux-autocomplete--autocomplete-no-results-found-text-value="No results found" data-symfony--ux-autocomplete--autocomplete-no-more-results-text-value="No more results" tabindex="0"><option value="1" selected="selected">Alice</option><option value="2">Bob</option></select></div>
                 */
                'autocomplete' => true,
                'required' => true,
                'class' => Author::class
            ])
            ->add('ratingAllowed')
        ;


        /**
         * Listening the PRE_SET_DATA alone is not enough to add the field on consecutive reloads - the POST_SUBMIT is
         * the key.
         *
         * @see https://symfony.com/doc/current/form/dynamic_form_modification.html#dynamic-generation-for-submitted-forms
         */
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            /** @var Post $post */
            $post = $event->getData();
            $form = $event->getForm();

            if ($post->isRatingAllowed()) {
                $form->add('ratingValue');
            }
        });

        $builder->get('ratingAllowed')->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {

            /** @var bool $ratingAllowed */
            $ratingAllowed = $event->getForm()->getData();
            /** @var FormInterface $parentForm */
            $parentForm = $event->getForm()->getParent();

            if ($ratingAllowed) {
                $parentForm->add('ratingValue');
            } elseif ($parentForm->has('ratingValue')) {
                // If the initial value was true, the field has been added during PRE_SET_DATA. If it was submitted as
                // false (the user un-ticked the box), we have to remove that field.
                $parentForm->remove('ratingValue');
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
            'attr' => ['novalidate' => true]
        ]);
    }
}
