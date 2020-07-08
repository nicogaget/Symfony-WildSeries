<?php

namespace App\Form;

use App\Entity\Actor;
use App\Entity\Category;
use App\Entity\Program;
use Doctrine\DBAL\Types\TextType;
use Laminas\Code\Generator\PropertyGenerator;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
                'label' => 'Titre',
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
            ->add('actors', EntityType::class, [
                'class' => Actor::class,
                'choice_label' => 'name',
                'expanded' => true,
                'multiple' => true,
                'by_reference' => false
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
