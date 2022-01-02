<?php

namespace Participant\Form;

use Zend\Form\Form;
use Doctrine\Common\Collections\ArrayCollection;

class ParticipantForm extends Form
{
    public function __construct($name = null)
    {

        parent::__construct('user');

        $this->setAttribute('class', 'form-horizontal');

        $this->add([
            'name' => 'id',
            'type' => 'Hidden',
        ]);

        $this->add([
            'name'    => 'firstname',
            'type'    => 'Text',
            'options' => [
                'label' => 'Prénom',
            ],
        ]);

        $this->add([
            'name'    => 'lastname',
            'type'    => 'Text',
            'options' => [
                'label' => 'Nom',
            ],
        ]);

        $this->add([
            'name'    => 'sex',
            'type'    => 'Radio',
            'options'    => [
                'label'            => 'Sexe',
                'label_attributes' => ['class' => 'checkbox-inline'],
                'value_options'    => [
                    [
                        'value'      => 'male',
                        'label'      => 'Homme',
                    ],
                    [
                        'value'      => 'female',
                        'label'      => 'Femme',
                    ]
                ]
            ],
        ]);
        
        $this->add([
            'name'    => 'event',
            'type'    => 'Radio',
            'options'    => [
                'label'            => 'Evenement',
                'label_attributes' => ['class' => 'checkbox-inline'],
                'value_options'    => [
                    [
                        'value'      => '1',
                        'label'      => 'Course de 12km',
                    ],
                    [
                        'value'      => '2',
                        'label'      => 'Semi Marathon',
                    ]
                ]
            ],
        ]);

        $this->add([
            'name'    => 'time',
            'type'    => 'Time',
            'attributes' =>[
                'min'  => '00:00:00',
                'max'  => '23:59:59',
                'step' => '1',
                'value'=> '00:00:00'
            ],
            'options' => [
                'label' => 'Temps',
                'format'=> 'H:i:s',
            ],
        ]);

        $this->add([
            'name'       => 'submit',
            'type'       => 'submit',
            'attributes' => [
                'class' => 'btn btn-primary',
                'value' => 'Sauvegarder'
            ],
        ]);
    }
}