<?php

namespace App\Form;

use App\Entity\QrCodeParameter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QrCodeParameterType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('data', Type\TextareaType::class)
            ->add('format', Type\ChoiceType::class, [
                'choices' => [
                    'png' => 'png',
                    'jpg' => 'jpg',
                    //'wepb' => 'wepb',
                    'svg' => 'svg',
                ],
            ])
            ->add('eccLevel', Type\ChoiceType::class, [
                'choices' => [
                    'Lowest' => 'L',
                    'Medium' => 'M',
                    'Quality' => 'Q',
                    'Highest' => 'H',
                ],
            ])

            // ---------- LOGO ----------
            ->add('logoUrl', Type\UrlType::class)
            ->add('logoSpaceWidth',  Type\IntegerType::class)
            ->add('logoSpaceHeight', Type\IntegerType::class)

            ->add('scale', Type\IntegerType::class)

            ->add('color1',  Type\ColorType::class)
            ->add('color2',  Type\ColorType::class)
            ->add('color3',  Type\ColorType::class)
            ->add('color4',  Type\ColorType::class)
            ->add('bgColor', Type\ColorType::class)
            ->add('transparent', Type\CheckboxType::class)

            ->add('drawCircularModules', Type\CheckboxType::class)
            ->add('keepAsSquare', Type\CheckboxType::class)
            ->add('circleRadius', Type\IntegerType::class)
        ;

        foreach ($builder->all() as $child) {
            $child->setRequired(false);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => QrCodeParameter::class,
        ]);
    }

    public function getBlockPrefix(): string
    {
        return '';
    }

}
