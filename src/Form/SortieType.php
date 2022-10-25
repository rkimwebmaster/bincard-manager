<?php

namespace App\Form;

use App\Entity\Sortie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;


class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('date')
            ->add('machineNumber')
        ->add('client',null,[
                'disabled'=>'disabled',
            ])
            ->add('iddNumber')
            ->add('ligneSorties',CollectionType::class,[
                'entry_type'=>LigneSortieType::class,
                'allow_add'=>true,
                'allow_delete' => true,
                'by_reference'=>false,
                'label'=>false,
                ]			
                )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
