<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;


class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            
            ->add('nom')
            ->add('prenom')
            ->add('dateDeNaissance', BirthdayType::class)
            -> add('password', RepeatedType::class, array(
              'type' => PasswordType::class,
              'invalid_message' => 'Le mot de passe ne correspond pas',
              'first_options' => array('label' => 'Mot de passe'),
              'second_options' => array('label' => 'Confimer le mot de passe')
            ))
            -> add('email')
            -> add('sexe', ChoiceType::class, array(
              'choices' => array(
                'Homme' => 'm',
                'Femme' => 'f'
              ),
            ))
            -> add('envoyer', SubmitType::class, [
                'attr' => ['name' => 'Envoyer']
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
