<?php

namespace App\Form;

use App\Entity\Site;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;


class SiteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('designation')
            ->add('code',null,[
                'disabled'=>'disabled'
            ])
            ->add('isWarehouse')
            ->add('adresse',null,[
                'label'=>'Adresse ',
                'help'=>'Cochez si warehouse  ',

            ])
            ->add('telephone',TelType::class)
            ->add('adresse',null,[
                'label'=>'Adresse ',
                'help'=>'Numéro, avenue , quartier  ',

            ])
            ->add('email')
            ->add('ville')
                    ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Site::class,
        ]);
    }
}
