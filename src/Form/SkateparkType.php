<?php

namespace App\Form;

use App\Entity\SkateParks;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SkateparkType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('region', ChoiceType::class, array(
                'choices' => array(
                    'Auvergne-Rhône-Alpes' => 'Auvergne-Rhône-Alpes',
                    'Bourgogne-Franche-Comté' => 'Bourgogne-Franche-Comté',
                    'Bretagne' => 'Bretagne',
                    'Centre-Val-de-Loire' => 'Centre-Val-de-Loire',
                    'Corse' => 'Corse',
                    'Grand-Est' => 'Grand-Est',
                    'Hauts-de-France' => 'Hauts-de-France',
                    'Île-de-France' => 'Île-de-France',
                    'Normandie' => 'Normandie',
                    'Nouvelle-Aquitaine' => 'Nouvelle-Aquitaine',
                    'Occitanie' => 'Occitanie',
                    'Pays-de-la-Loire' => 'Pays-de-la-Loire',
                    'Provence-Alpes-Côte-d\'Azur' => 'Provence-Alpes-Côte-d\'Azur',
                )
            ))
            ->add('ville')
            ->add('contenu')
            ->add('imageFile', FileType::class)
            ->add('adresse')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SkateParks::class,
        ]);
    }
}
