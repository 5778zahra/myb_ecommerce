<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname',TextType::class, [
                'label'=> 'Votre prénom',
                'attr' => [
                    'placeholder'=> 'Merci de saisir votre prénom'
                ]
            ])
            ->add('lastName', TextType::class, [
                'label'=> 'Votre nom',
                'attr' => [
                    'placeholder'=> 'Merci de saisir votre nom'
                    // 'maxLength' => 45
                ]
            ])
            ->add('email', EmailType::class, [
                'label'=> 'Votre email',
                'attr' => [
                    'placeholder'=> 'Merci de saisir votre adresse email ' 
                    // 'maxLength'=> 100
                ]
            ])
            ->add('subject', ChoiceType::class, [
                'choices' => [
                    '--Sélectionner --' => '',
                    'signaler un problème' => 'problème',
                    'postuler' => 'postuler',
                    'service apres vente'=> 'SAV',
                    'autre' => 'divers'
                ]

            ])
            ->add('message', TextareaType::class, [
                'label'=> 'Votre prénom',
                'attr' => [
                    'placeholder'=> 'Merci de saisir votre prénom'
                    // 'maxLength' => 65545
                ]
            ])
            // ->add('attachment', FileType::class, [
            //     'required' => false,
            //     'help' => 'PNG, JPEG, WEBP ou PDF - 2 Mo maximum',
            //     'constraints' => [
            //         new File([
            //             'maxSize' => '2M',
            //             'maxSizeMessage' => 'Le fichier est trop volumineux ({{ size }} {{ suffix }}). La taille maximale
            //             autorisé est de {{ limit }} {{ suffix }}',
            //             'mimeTypes' => [
            //                 'image/png',
            //                 'image/jpg',
            //                 'image/jpeg',
            //                 'image/jp2',
            //                 'image/webp',
            //                 'application/pdf'
            //             ],
            //             'mimeTypesMessage' => 'Le format de fichier est invalide ({{ type }}).Les types autorisés sont : {{ types }}. '

            //         ])

                    
            //    ]
            //])
            ->add('submit', SubmitType::class, [
                'label' => 'Envoyer',
                'attr' => [
                    'class'=> 'btn-block btn-success'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
