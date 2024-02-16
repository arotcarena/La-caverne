<?php

namespace App\Form;

use App\Entity\Brand;
use App\Entity\Picture;
use App\Entity\Product;
use App\Entity\Category;
use Doctrine\ORM\PersistentCollection;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Contracts\EventDispatcher\Event;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('brand', EntityType::class, ['class' => Brand::class, 'choice_label' => 'name', 'choice_value' => 'name'])
            ->add('model')
            ->add('description', TextareaType::class)
            ->add('price', MoneyType::class)
            ->add('stock')
            ->add('category', EntityType::class, ['class' => Category::class, 'choice_label' => 'name', 'choice_value' => 'name'])
        ;


    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
