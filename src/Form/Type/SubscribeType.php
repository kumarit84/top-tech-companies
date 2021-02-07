<?php

namespace App\Form\Type;

use App\Entity\Subscribe;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class SubscribeType extends AbstractType
{
    const SPORTS = 'sports';
    const INTERNATIONAL = 'international';
    const NATIONAL = 'national';
    const CINEMA = 'cinema';

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('email',TextType::class)
            ->add('news', ChoiceType::class, [
                'choices' => [
                    'Sports' => self::SPORTS,
                    'International' => self::INTERNATIONAL,
                    'National' => self::NATIONAL,
                    'Cinema' => self::CINEMA,
                ],
                'expanded'  => true,
                'multiple'  => true,
                'required' => true,
            ])
        ;
    }
}
