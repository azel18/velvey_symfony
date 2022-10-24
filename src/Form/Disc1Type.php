<?php

namespace App\Form;

use App\Entity\Disc;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class Disc1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title',TextType::class,[
                'label'=> 'Titre'
            ])
            ->add('year',IntegerType::class,[
                'label'=> 'Année'
            ])
            ->add('picture2', FileType::class, [
                'label' => 'Photo de profil',
                //unmapped => fichier non associé à aucune propriété d'entité, validation impossible avec les annotations
                'mapped' => false,
                // pour éviter de recharger la photo lors de l'édition du profil
                'required' => false,
                'constraints' => [
                    new Image([
                        'maxSize' => '2000k',

                        'mimeTypesMessage' => 'Veuillez insérer une photo au format jpg, jpeg ou png'
                    ])
                ]
            ])
            ->add('label')
            ->add('genre')
            ->add('price')
            ->add('artist')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Disc::class,
        ]);
    }
}
