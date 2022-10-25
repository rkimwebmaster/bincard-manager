<?php

namespace App\Form;

use App\Entity\LigneRMensuel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LigneRMensuelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('quantiteInitiale')
            ->add('quantiteEntree')
            ->add('quantiteEntreeSpeciale')
            ->add('quantiteEntreeTransfert')
            ->add('quantiteEntreeReuse')
            ->add('sortieClient')
            ->add('sortieSpeciale')
            ->add('quantiteSortieTransfert')
            ->add('quantiteFinale')
            ->add('pn')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => LigneRMensuel::class,
        ]);
    }
}
