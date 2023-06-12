<?php
// src/Form/CheckboxType.php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType as SymfonyCheckboxType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CheckboxType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'label' => false,
            'required' => false,
        ]);
    }

    public function getParent(): ?string
    {
        return SymfonyCheckboxType::class;
    }

}