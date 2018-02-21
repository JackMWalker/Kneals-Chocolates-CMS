<?php

namespace App\Form;

use App\Entity\Allergy;
use App\Entity\PreviewItem;
use App\Entity\Product;
use App\Entity\Selection;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Created by PhpStorm.
 * User: jackwalker
 * Date: 03/02/2018
 * Time: 12:39
 */

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
                'constraints' => array(
                    new NotBlank()
                )
            ))
            ->add('price', MoneyType::class, array(
                'currency' => 'GBP'
            ))
            ->add('cost', MoneyType::class, array(
                'currency' => 'GBP'
            ))
            ->add('weight', IntegerType::class)
            ->add('description', TextType::class)
            ->add('isLive', CheckboxType::class, array(
                'required' => false
            ))
            ->add('postagePrice', MoneyType::class, array(
                'currency' => 'GBP'
            ))
            ->add('postageCost', MoneyType::class, array(
                'currency' => 'GBP'
            ))
            ->add('stock', IntegerType::class)
            ->add('selection', EntityType::class, array(
                'class' => Selection::class,
                'choice_label' => 'name',
                'multiple' => false,
                'expanded' => true,
                'required' => false
            ))
            ->add('allergies', EntityType::class, array(
                'class' => Allergy::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true
            ))
            ->add('images', CollectionType::class, array(
                'entry_type' => ProductImageType::class,
                'entry_options' => array('label' => false),
                'by_reference' => false,
                'allow_add' => true,
                'allow_delete' => true
            ))
            ->add('dynamicSelection', CheckboxType::class, array(
                'label' => 'Can the user choose options from the selection provided? (E.g the bars selection)',
                'required' => false
            ))
            ->add('dynamicSelectionNumber', IntegerType::class, array(
                'label' => 'Number of options the user can select (only required if above is selected)',
                'required' => false
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}