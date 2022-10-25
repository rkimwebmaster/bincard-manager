<?php

namespace App\Form;

use App\Entity\Sortie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class SortieDamageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('date',null,[

           // 'disabled'=>'disabled'
        ])
        ->add('observation', TextType::class,[
            'label'=>'Receiver',
            'required'=>true,
            'attr'=>[
                'placeholder'=>'Nom du bénéficiaire',
            ]
        ])
        ->add('ligneSorties',CollectionType::class,[
            'entry_type'=>LigneSortieDamageType::class,
            'allow_add'=>true,
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
