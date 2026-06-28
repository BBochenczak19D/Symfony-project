<?php

/**
 * This file is part of the SI project.
 *
 * (c) Students
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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

/**
 * User form type.
 */
class UserType extends AbstractType
{
    /**
     * Builds the form.
     *
     * @param FormBuilderInterface $builder Form builder
     * @param array                $options Form options, supports 'require_password'
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('email', EmailType::class, [
            'label' => 'label.email',
            'required' => true,
            'empty_data' => '',
            'attr' => ['class' => 'form-control'],
        ]);

        $passwordConstraints = [];
        if ($options['require_password']) {
            $passwordConstraints[] = new NotBlank(['message' => 'label.require_password']);
        }
        $passwordConstraints[] = new Length([
            'min' => 6,
            'minMessage' => 'label.password_too_short',
            'max' => 255,
        ]);

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
            'invalid_message' => 'label.not_equal_password',
        ]);
    }

    /**
     * Configures the options for this type.
     *
     * @param OptionsResolver $resolver Options resolver, sets 'data_class' and 'require_password'
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'require_password' => true,
        ]);
    }
}
