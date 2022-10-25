<?php

namespace App\Form;

use App\Entity\LigneStockControle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LigneStockControleType extends AbstractType
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
            ->add('ijdNumber')
            ->add('quantitySold')
            ->add('totalBalance')
            ->add('createdAt')
            ->add('updatedAt')
            ->add('numeroDNN')
            ->add('numeroMouvement')
            ->add('site')
            ->add('produitSite')
            ->add('ligneTransfert')
            ->add('ligneStockControle')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => LigneStockControle::class,
        ]);
    }
}
