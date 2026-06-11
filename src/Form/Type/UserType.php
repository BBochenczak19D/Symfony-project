<?php

namespace App\Form\Type;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('email', EmailType::class, [
            'label' => 'label.email',
            'required' => true,
            'attr' => ['class' => 'form-control'],
        ]);

        $passwordConstraints = [];
        if ($options['require_password']) {
            $passwordConstraints[] = new NotBlank(['message' => 'Podaj hasło']);
        }
//        $passwordConstraints[] = new Length([
//            'min' => 6,
//            'minMessage' => 'Hasło musi mieć co najmniej {{ limit }} znaków',
//            'max' => 128,
//        ]);

        $builder->add('plainPassword', RepeatedType::class, [
            'type' => PasswordType::class,
            'mapped' => false,
            'required' => $options['require_password'],
            'first_options' => [
                'label' => 'label.password',
                'attr' => ['class' => 'form-control'],
                'constraints' => $passwordConstraints,
            ],
            'second_options' => [
                'label' => 'label.repeat_password',
                'attr' => ['class' => 'form-control'],
            ],
            'invalid_message' => 'Hasła muszą się zgadzać.',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'require_password' => true,
        ]);
    }
}
