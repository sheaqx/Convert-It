<?php

namespace App\Form;

use App\Entity\Picture;
use App\Service\Upload;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class UploadType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', FileType::class, [
                'label' => 'Image',
                'required' => true,
                'constraints' => [
                    new File([
                        //max size of 2mb
                        'maxSize' => '2000k',
                        //allowed images
                        'mimeTypes' => [
                            'image/png',
                            'image/jpeg',
                            'image/webp',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image',
                    ])
                ],
            ])
            ->add('convertTo', ChoiceType::class, [
                'mapped' => false,
                'choices' => [
                    'choose' => 1,
                    'webp' => 2,
                    'png' => 3,
                    'jpg' => 4,
                ]
            ])
            ->add('tag', TextType::class, ['required' => true,])
            ->add('description', TextType::class, ['required' => true,])
            ->add('upload', SubmitType::class, ['label' => 'Convert It !', 'attr' => ['class' => 'btn btn-secondary']]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Picture::class,
        ]);
    }
}
