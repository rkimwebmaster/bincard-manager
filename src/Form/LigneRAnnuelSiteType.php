<?php

namespace App\Form;

use App\Entity\LigneRAnnuelSite;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LigneRAnnuelSiteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('quantiteInitiale')
            ->add('quantiteEntree')
            ->add('quantiteSortie')
            ->add('solde')
            ->add('pn')
            ->add('rapport')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => LigneRAnnuelSite::class,
        ]);
    }
}
