<?php

namespace App\Form;

use App\Entity\Entree;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;


class EntreeTypejnjhjh extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('date',null,[

            'disabled'=>'disabled'
        ])
        ->add('numeroBonFournisseur',null,[

            'disabled'=>false
        ])
        // ->add('fournisseur',null,[

        //     'disabled'=>'disabled'
        // ])
        //->add('site')
        ->add('ligneEntrees',CollectionType::class,[
            'entry_type'=>LigneEntreeType::class,
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
            'data_class' => Entree::class,
        ]);
    }
}
