<?php

namespace App\Form;

use App\Entity\Author;
use App\Entity\Book;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('year')
            ->add('isbn', TextType::class, [
                'label' => 'ISBN',
            ])
            ->add('pages')
            ->add('authors', EntityType::class, [
                'label'        => 'Authors (optional)',
                'class'        => Author::class,
                'multiple'     => true,
                'by_reference' => false,
                'required'     => false,
            ])
            ->add('coverImage', FileType::class, [
                'label'       => 'Cover image (optional)',
                'mapped'      => false,
                'required'    => false,
                'help'        => 'Supported formats: jpeg, png. Max size: 2 MB',
                'constraints' => [
                    new File([
                        'maxSize'          => '2M',
                        'mimeTypes'        => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
