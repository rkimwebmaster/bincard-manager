<?php

namespace App\Form;

use App\Entity\ProduitSite;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProduitSiteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('produit',null,[
            'disabled'=>'disabled'
        ])
            ->add('quantite',null,[
                'disabled'=>'disabled'
            ])
            ->add('stockAlerte')
            ->add('site',null,[
                'disabled'=>'disabled'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProduitSite::class,
        ]);
    }
}
