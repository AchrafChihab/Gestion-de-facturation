<?php

namespace App\Form;

use App\Entity\Commande;
use App\Form\LigneCommandeType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class CommandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('statue')
        ->add('client')
        ->add('ligneCommandes', CollectionType::class, [
        'entry_type' => LigneCommandeType::class,
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
            'data_class' => Commande::class,
        ]);
    }
}
