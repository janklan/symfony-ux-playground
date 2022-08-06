<?php

namespace App\Form;

use App\Entity\Author;
use App\Entity\Post;
use App\Model\EntityDto\Task\TaskCreateDto;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
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

            /**
             * I'm adding the field here and removing it later if the data says rating !allowed. I tried the other way
             * around too - adding it in the event listener instead.
             *
             * I also tried to add a hidden input here and then change it to the a NumberType in the listener, but that
             * didn't work either.
             */
            ->add('ratingValue')
        ;

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            /** @var Post $post */
            $post = $event->getData();
            $form = $event->getForm();

            /**
             * This event clearly fires during the initial form rendering (the ratingValue is removed for for Post 1),
             * but nothing happens on consecutive "Live reloads".
             */

            if (!$post->isRatingAllowed()) {
                $form->remove('ratingValue');
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
