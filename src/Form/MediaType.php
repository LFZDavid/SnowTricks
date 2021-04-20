<?php

namespace App\Form;

use App\Entity\Media;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Validator\Constraints\NotNull;

class MediaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', ChoiceType::class, [
                'choices' => [
                    '--type--' => null,
                    'image' => 'img',
                    'video' => 'video',
                ],
                'label' => false,
                'attr' => [
                    'class' => "form-control media-type",
                ],
            ])
            ->add('url', UrlType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => 'url',
                    'class' => "form-control trick-video",
                ],
            ])
            ->add('file', FileType::class, [
                'label' => false,
                'mapped' => true,
                'required' => false,
                'attr' => [
                    'class' => 'form-control trick-img',
                ],
                'constraints' => [
                    new File([
                        'maxSize' => '2048k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Format accepté : .jpg ou .png',
                    ])
                ]
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Media::class,
        ]);
    }
}
