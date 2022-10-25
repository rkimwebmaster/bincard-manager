<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('username',null,[
            "disabled"=>"disabled",
        ])
        ->add('siteGeres',null,[
            'label'=>'Sites à réaffecter'
        ])
        ->add('nom')
        ->add('postnom')
        ->add('email')
        ->add('telephone',null,[
            'label'=>'Téléphone '
        ])
        ->add('adresse')
        ->add('roles', ChoiceType::class, [
                       'choices' => [
                       'Encodeur' => 'ROLE_ENCODEUR',
                       'Auditeur ' => 'ROLE_QUOTATION',
                       'Administrateur' => 'ROLE_ADMIN',
                       ],
                       'expanded' => false,
                       'multiple' => true,
                       'label' => 'Les Rôles des users du systeme'
                       ])
        ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
