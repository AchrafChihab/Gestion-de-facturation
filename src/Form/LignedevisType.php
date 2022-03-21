<?php

namespace App\Form;

use App\Entity\Lignedevis;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class LignedevisType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('qte')
            ->add('prix')
            ->add('datede', DateTimeType::class, [ 'date_label' => 'date debut ',])
            ->add('datea', DateTimeType::class, [ 'date_label' => 'date fin',])
            ->add('service',null, [
                'attr' => ['class' => 'choise_service',
                            'onchange'=> 'getComboA(this)']]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Lignedevis::class,
        ]);
    }
}
