<?php

namespace App\Form;

use App\Entity\Brand;
use App\Entity\Category;
use App\Entity\ProductFilter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;


class ProductFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->setMethod('get')
            ->add('brand', EntityType::class, ['class' => Brand::class, 'label' => 'Filtrer par marque', 'choice_label' => 'name', 'required' => false])
            ->add('category', EntityType::class, ['class' => Category::class, 'label' => 'Filtrer par catégorie', 'choice_label' => 'name', 'required' => false])
            ->add('price_order', ChoiceType::class, [
                'label' => 'Trier par ordre de prix',
                'choices' => [
                    'du - cher au + cher' => 'asc',
                    'du + cher au - cher' => 'desc',
                ], 'required' => false
                ])
            ->add('price_min', NumberType::class, ['label' => 'prix mini', 'required' => false])
            ->add('price_max', NumberType::class, ['label' => 'prix maxi', 'required' => false])
            ->add('qSearch', TextType::class, ['label' => 'Recherche par mot-clef, marque, modèle, etc...', 'required' => false])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProductFilter::class,
        ]);
    }
}
