<?php

namespace App\Form;

use App\Entity\Controle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;


class ControleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date')
            ->add('code',null,[
                'disabled'=>'disabled',
            ])
            ->add('observationFinale')
            ->add('ligneControles',CollectionType::class,[
                'entry_type'=>LigneControleType::class,
                'allow_add'=>true,
                'allow_delete'=>true,
                'by_reference'=>false,
                'label'=>false,
                ]			
                )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Controle::class,
        ]);
    }
}
