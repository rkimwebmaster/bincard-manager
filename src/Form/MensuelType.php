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
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


class MensuelType extends AbstractType
{
    private $tokenStorage;
    private $tableau=[];
    public function __construct(TokenStorageInterface $tokenStorage)
    {
      $this->tokenStorage=$tokenStorage;
      $this->tableau=range(2020,2030);
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $site=$this->tokenStorage->getToken()->getUser()->getSiteActif();
        $tableau=$this->tableau;
        $builder
        // ->add('solde')
        // ->add('produitSite')  
          
      ->add('mois', ChoiceType::class, [
        'attr'=>[
            'class'=>'select2'
        ],
        'choices' => [
        'Janvier' => '1',
        'Février' => '2',
        'Mars' => '3',
        'Avril ' => '4',
        'Mai ' => '5',
        'Juin' => '6',
        'Juillet' => '7',
        'Août' => '8',
        'Septembre' => '9',
        'Octobre ' => '10',
        'Novembre' => '11',
        'Decembre' => '12',
        ],
        'expanded' => false,
        'multiple' => false,
        'label' => 'Mois',
        'help' => 'Choisir le mois du rapport'
        ])

        ->add('annee', ChoiceType::class, [
            'attr'=>[
                'class'=>'select2',
            ],
            'choices' => [
                '2020' => '2020',
                '2021' => '2021',
                '2022' => '2022',
                '2023 ' => '2023',
                '2024 ' => '2024',
                '2025' => '2025',
                '2026' => '2026',
                '2027' => '2027',
                '2028' => '2028',
                ],
            
            'help' => 'Choisir année du rapport'
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
