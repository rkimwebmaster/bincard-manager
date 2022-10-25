<?php

namespace App\Form;

use App\Entity\LigneSortie;
use App\Entity\ProduitSite;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class LigneSortieType extends AbstractType
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
              'class' => ProduitSite::class,
              'query_builder' => function ( EntityRepository $er ) use ($site) {
          return $er->createQueryBuilder('c')
          ->where('c.site = :val')
         // ->where('c.quantite > ', 0 )
          ->setParameter('val', $site)
            ->orderBy('c.id', 'ASC');
          },
      ])      
          // ->add('transfert')
         ->add('quantite')

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => LigneSortie::class,
        ]);
    }
}
