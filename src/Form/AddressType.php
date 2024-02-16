<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\ChoiceList;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('id', HiddenType::class, ['required' => false])
            ->add('civility', ChoiceType::class, [
                'choices'  => [
                    'M.' => 'Monsieur',
                    'Mme' => 'Madame',
                    'Mlle' => 'Mademoiselle',
                ], 
                'required' => false
                ])
            ->add('first_name')
            ->add('last_name')
            ->add('number')
            ->add('way')
            ->add('city')
            ->add('postal_code')
            ->add('delivery', CheckboxType::class, ['required' => false])
            ->add('invoice', CheckboxType::class, ['required' => false])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
