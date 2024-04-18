<?php

namespace App\Form;
use App\Entity\User; 
use App\Entity\Reservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Evenement;
use Symfony\Bridge\Doctrine\Form\Type\EntityType; // Import correct du type EntityType

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomparticipant')
            ->add('prenomparticipant')
            ->add('email')
            ->add('numtel')
            ->add('typedeparticipant')
            ->add('idUser', EntityType::class, [  // Utilisation du type EntityType
                'class' =>'App\Entity\User',  // Spécifiez l'entité à laquelle ce champ est associé
                'choice_label' => 'id',  // Le champ de l'entité User à afficher
                'placeholder' => 'Choisir un utilisateur',  // Texte à afficher comme option par défaut
            ])
            ->add('event', EntityType::class, [
                'class' => 'App\Entity\Evenement',
                'choice_label' => 'id', // You can change this to any property you want to display
                'placeholder' => 'Choisir un evenement',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
             'data_class' => Reservation::class,
        ]);
    }
}
