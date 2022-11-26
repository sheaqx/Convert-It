<?php

namespace App\Form;

use App\Entity\Picture;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\ChoiceList\Factory\Cache\ChoiceLabel;
use Symfony\Component\Form\AbstractType;
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
                'label' => 'Images',
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
            ->add('tag', TextType::class, ['required' => true,])
            ->add('description', TextType::class, ['required' => true,])
            //adding User dropdown until login is finished to add into user_id
            ->add('user', EntityType::class, [
                'class' => User::class,
                'required' => true,
                "choice_label" => 'pseudo'
            ])
            ->add('upload', SubmitType::class, ['label' => 'Convert It !', 'attr' => ['class' => 'btn btn-secondary']]);
        // ->add('slug')
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Picture::class,
        ]);
    }
}
