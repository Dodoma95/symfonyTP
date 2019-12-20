<?php

namespace App\Form;

use App\Entity\Post;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('message', TextareaType::class,
                    [   'label' => 'Texte',
                        'attr' => ['rows' => '15']
                    ])
            ->add('createAt', DateType::class, [
                'label' => 'Date de création',
                'widget' => 'single_text'
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'fullName',
                'multiple' => false,
                'expanded' => false
            ])
            ->add('submit', SubmitType::class,
                ["label" => "Valider", "attr" => ["class" => "btn btn-success"]])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
