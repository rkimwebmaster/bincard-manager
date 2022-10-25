<?php

namespace App\Form;

use App\Entity\LigneBillcard;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LigneBillcardType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date')
            ->add('machineNumber')
            ->add('deliveryNoteNumber')
            ->add('quantityReceived')
            ->add('supplier')
            ->add('customer')
            ->add('idNumber')
            ->add('quantitySold')
            ->add('totalBalance')
            ->add('site')
            ->add('produitSite')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => LigneBillcard::class,
        ]);
    }
}
