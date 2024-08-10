<?php

declare(strict_types=1);

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TeacherType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('email', EmailType::class)
            ->add('firstname', TextType::class, ['label' => 'Prénom'])
            ->add('lastname', TextType::class, ['label' => 'Nom'])
            ->add('grade', TextType::class, ['label' => 'Classe'])
            ->add('is_available_for_appointment', CheckboxType::class, [
                'label' => 'Accepte les rendez-vous',
                'help' => 'Si vous cochez cette case, les élèves pourront prendre rendez-vous avec vous.',
                'required' => false,
            ])
            ->add('save', SubmitType::class, ['label' => 'Enregistrer']);
    }
}
