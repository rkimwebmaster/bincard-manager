<?php

namespace App\Form;

use App\Entity\Transfert;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;


class TransfertType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date')
            //->add('ceatedAt')
           // ->add('updatedAt')
            ->add('code',null,[
                'disabled'=>'disabled'
            ])
            //->add('user')
           // ->add('siteEnvoie')
           // ->add('siteReception')
            ->add(
                'ligneTransferts',
                CollectionType::class,
                [
                    'entry_type' => LigneTransfertType::class,
                    'allow_add' => true,
                    'by_reference' => false,
                    'label' => false,
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Transfert::class,
        ]);
    }
}
