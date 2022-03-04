<?php

namespace App\Form;

use App\Entity\Devis;
use App\Form\LignedevisType;
use App\Form\LignefactureType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class DevisType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('statue')
            ->add('client')
            ->add('lignedevis', CollectionType::class, [
                'entry_type' => LignedevisType::class,
                // 'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'label' => 'les services',
                'by_reference' => false,
                ])      
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Devis::class,
        ]);
    }
}
