<?php

namespace App\Form;

use App\Entity\Episode;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EpisodeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',null, [
                'label' => 'Titre'
            ])
            ->add('number', null, [
                'label' => "Numéro d'épisode"
            ])
            ->add('synopsis', null, [
                'label' => 'Résumé'
            ])
            ->add('season', null, [
                'choice_label' => 'number',
                'label' =>'N° de saison'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Episode::class,
        ]);
    }
}
