<?php

namespace App\Form;

use App\Entity\Commentaire;
use App\Entity\Utilisateur;
use App\Entity\Vehicule;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('note')
            ->add('message')
            // ->add('utilisateur', EntityType::class, [
            //     'class' => Utilisateur::class,
            //     'choice_label' => 'id',
            // ])
            // ->add('vehicule', EntityType::class, [
            //     'class' => Vehicule::class,
            //     'choice_label' => 'id',
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commentaire::class,
        ]);
    }
}
