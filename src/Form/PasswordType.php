<?php

namespace App\Form;

use App\Entity\Password;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PasswordsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options) // Création d'un formulaire
    {
        $builder
            ->add('oldPassword', PasswordType::class)//ancien mot de passe
            ->add('newPassword', RepeatedType::class, array(
                'type' => PasswordType::class
            ));   //nouveau mot de passe
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Password::class,
        ]);
    }
}