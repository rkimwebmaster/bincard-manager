<?php

namespace App\Form;

use App\Entity\Fournisseur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;


class FournisseurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        // ->add('code',null,[
        //     'disabled'=>'disabled', 
        // ])
        ->add('noms')
->add('telephone',TelType::class)
            ->add('adresse',null,[
                'label'=>'Adresse ',
                'help'=>'Numéro, avenue , quartier  ',

            ])
            ->add('email')
            ->add('ville',null,[
                'attr'=>[
                    'class'=>'select2'
                ],
            ])        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Fournisseur::class,
        ]);
    }
}
