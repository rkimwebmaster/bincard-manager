<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use App\Entity\ProduitSite;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


class SearchBillcardType extends AbstractType
{
    private $tokenStorage;
    public function __construct(TokenStorageInterface $tokenStorage)
    {
      $this->tokenStorage=$tokenStorage;        

    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $site=$this->tokenStorage->getToken()->getUser()->getSiteActif();
        $builder
        // ->add('solde')
        // ->add('produitSite')  
            ->add('produitSite', EntityType::class , [
                'attr'=>[
                    'class'=>'select2'
                ],
                'help' => 'Ce champ est obligatoire',
                'mapped' => false,
                'class' => ProduitSite::class,
                'query_builder' => function ( EntityRepository $er ) use ($site) {
          return $er->createQueryBuilder('c')
            ->where('c.site = :val')
            ->setParameter('val', $site)
            ->orderBy('c.id', 'ASC');
          },
      ])  
      ->add('mouvement', ChoiceType::class, [
        'attr'=>[
            'class'=>'select2'
        ],
        'choices' => [
        'Tous' => '10',
        'Entrées fournisseurs' => '1',
        'Entrées reuses' => '2',
        'Entrées spéciales ' => '3',
        'Entrées Transferts ' => '8',
        'Sorties clients ' => '5',
        'Sorties damages' => '6',
        'Sorties speciale' => '7',
        'Sorties trasferts' => '4',
        'Stock take' => '9',
        ],
        'expanded' => false,
        'multiple' => false,
        'label' => 'Mouvement',
        'help' => 'Tous les produit par defaut'
        ])
        ->add('dateDebut', DateType::class,[
            "mapped"=>false,
            'widget' => 'single_text',
            'help' => 'Ce champ peut être laissé vide'
        ])
            ->add('dateFin', DateType::class,[
            "mapped"=>false,
            'widget' => 'single_text',
            'help' => 'Ce champ peut être laissé vide'

        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            //'validation_groups' => false,
        ]);
    }
}
