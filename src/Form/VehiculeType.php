<?php

namespace App\Form;

use App\Entity\Vehicule;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VehiculeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('marque')
            ->add('modele')
            ->add('immatriculation')
            ->add('prixJournalier')
            ->add('statut')
            ->add('description')
            ->add('noteMoyenne')
            ->add('dateAjout', null, [
                'widget' => 'single_text',
            ])
            ->add('photo', FileType::class, [
                'label' => 'Photo du véhicule (JPG, PNG)',
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'accept' => 'image/*'
                ],
            ])    
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Vehicule::class,
        ]);
    }
}
