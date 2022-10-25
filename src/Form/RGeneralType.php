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


class RGeneralType extends AbstractType
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
        $builder
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
