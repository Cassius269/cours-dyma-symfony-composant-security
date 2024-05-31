<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('password', PasswordType::class)
            ->add('name')
            ->add('submit', SubmitType::class, [
                "label" => 'S\'inscrire'
            ])
            ->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $formEvent){
                $data=$formEvent->getdata();
                $form = $formEvent->getForm();
                if($data->getName() === 'Jeanne'){
                $form->add('surnom',TextType::class,[
                    'mapped' => false
                ]);
                }
            });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
