<?php

namespace App\Form;

use App\Entity\Section;
use App\Entity\Template;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SectionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('titre', TextType::class, [
            'label' => 'Titre :',
            'attr' => [
            'placeholder' => 'Nom de la section'
            ]
        ])
            ->add('template', EntityType::class, [
                'class' => Template::class,
                'choice_label' => 'name', 
                'expanded' => true,  // Transforme en boutons radio
                'multiple' => false, // Sélection unique
                'label' => 'Choisir la disposition',
            ])
            ->add('text', TextareaType::class, [
                'label' => 'Description :',
                'attr' => [
                    'placeholder' => 'Description de le section...'
                ],
            ])
            ->add('pic', FileType::class, [
                'label' => 'Image associé à la section :',
            ])
            ->add('number', IntegerType::class, [
                'label' => 'Ordre d\'apparition dans votre Portfolio :',
                'attr' => [
                    'min'  => '1'
                ]
            ]);
    }



    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Section::class,
        ]);
    }
}
