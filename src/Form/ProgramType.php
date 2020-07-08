<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Program;
use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class ProgramType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, [
                'label' => 'Titre'
            ])
            ->add('summary', null, [
                    'label' => 'Résumé'
            ])
            ->add('poster', null, [
                'label' => "Url de l'affiche"
            ])
            ->add('country', null, [
                'label' => 'Pays'
            ])
            ->add('year', null, [
                'label' => 'Année'
            ])
            ->add('category', null, [
                'choice_label' => 'name'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Program::class,
        ]);
    }
}
