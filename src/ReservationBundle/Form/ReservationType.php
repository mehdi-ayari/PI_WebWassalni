<?php

namespace ReservationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class ReservationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('destination' ,choiceType::class,[
                'choices'=>[
                    'selectionner votre destination'=>false,
                    'centre ville,Tunis'=>'centre ville,Tunis',
                    'Ariana'=>'Ariana',
                    'Megrine'=>'Megrine',
                    'Mourouj'=>'Mourouj',
                    'Bardo'=>'Bardo',
                    'Carthage'=>'Carthage',
                    'Ben arous'=>'Ben arous',
                ],
                'required'=>true])

            ->add('typeReservation',choiceType::class,[
                'choices'=>[
                    'selectionner votre voiture'=>false,
                    'Taxi'=>'Taxi',
                    'Privée'=>'Privée',
                    'camion'=>'camion',
                ],
                'required' =>true])

            ->add('userChauffeur');


    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ReservationBundle\Entity\Reservation'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'reservationbundle_reservation';
    }


}
