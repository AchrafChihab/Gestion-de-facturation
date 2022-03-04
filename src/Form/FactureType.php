<?php

namespace App\Form;

use App\Entity\Facture;
use App\Form\ServiceType;
use App\Entity\Lignefacture;
use App\Form\LignefactureType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class FactureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('statue')
            ->add('client')
            ->add('lignesfacture', CollectionType::class, [
            'entry_type' => LignefactureType::class,
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
            'data_class' => Facture::class,
        ]);
    }
}
