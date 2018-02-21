<?php

namespace App\Form;

use App\Entity\Allergy;
use App\Entity\PreviewItem;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Created by PhpStorm.
 * User: jackwalker
 * Date: 03/02/2018
 * Time: 12:39
 */

class PreviewItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
                'constraints' => array(
                    new NotBlank()
                )
            ))
            ->add('description', TextType::class)
            ->add('image', FileType::class, array(
                'label' => 'Image (jpg or png)',
                'required' => false
            ))
            ->add('allergies', EntityType::class, array(
                'class' => Allergy::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true
            ))
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PreviewItem::class,
        ]);
    }
}