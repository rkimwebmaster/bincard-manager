<?php

namespace App\Form;

use App\Entity\LigneEntree;
use App\Entity\Produit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class LigneEntreeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('produit',null,[
            'attr'=>[
                'class'=>' select2 ',
            ],
        ]) 
            ->add('quantite')
            //->add('solde')
            ->add('observation')
            //->add('entree')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => LigneEntree::class,
        ]);
    }
}
