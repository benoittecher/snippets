<?php

namespace App\Form;

use App\Entity\Message;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class MessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('objet', TextType::class, [
            'constraints' => [
                new NotBlank(['message' => 'Veuillez indiquer un objet.']),
                new Length([
                    'min' => 3,
                    'minMessage' => 'L\'objet doit contenir au moins 3 caractères',
                    'max' => 250,
                    'maxMessage' => 'L\'objet ne peut contenir plus de 250 caractères'
                ])
            ]
        ])
        ->add('corps', CKEditorType::class, [
            'constraints' => [
                new NotBlank(['message' => 'Veuillez saisir un message']),
                new Length([
                    'min' => 3,
                    'minMessage' => 'Le message doit contenir au moins 3 caractères',
                    'max' => 15000,
                    'maxMessage' => 'Le message ne peut contenir plus de 15000 caractères'
                ])
            ]/*,
            'attr' => [
                'id'=>"textArea",
                "readonly"=>"true" 
                ]
        ])
        ->add('corps', HiddenType::class,[
            'attr' => [
                'id' => "corps"
            ]*/
            ])
        
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Message::class,
        ]);
    }
}
