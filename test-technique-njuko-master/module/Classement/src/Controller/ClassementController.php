<?php

namespace Classement\Controller;

use Doctrine\ORM\EntityManager;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;


class ClassementController extends AbstractActionController
{

    /** @var EntityManager $entityManager */
    private $entityManager;

    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function indexAction()
    {
        $participants = $this->entityManager->getRepository('Application\Entity\Participant')->findAll();
        $events       = $this->entityManager->getRepository('Application\Entity\Event')->findAll();
        
        usort($participants, array($this,"orderByTime"));

        return new ViewModel(
            array(
                "participants" => $participants,
                "events"       => $events,
            ),
        );    
    }

    public function classementAction(){
        $participants = $this->entityManager->getRepository('Application\Entity\Participant')->findAll();

        $event_id     = (int) $this->params()->fromRoute('id', 0);
        $event        = $this->entityManager->getRepository('Application\Entity\Event')->find($event_id);
        $gender       = $this->params()->fromRoute('gender', '');
        $sorting      = $this->params()->fromRoute('sorting', '');

        //Select event by id
        /** @var \Application\Entity\Event $event */
        if (0 !== $event_id) {
            try {
                $participants = $this->selectRace($participants,$event_id); 
            } catch (\Exception $e) {
                return $this->redirect()->toRoute('participant/list');
            }
        }
            //Select participants by gender
        if("" != $gender ){
            try{
                if($gender == "femmes"){
                    $participants = $this->selectgender($participants,'female'); 
                }
                else if($gender == "hommes"){
                    $participants = $this->selectgender($participants,'male');
                } 
            } catch (\Exception $e) {
                return $this->redirect()->toRoute('participant/list');
            }
        }

        //Sorting options
        switch ($sorting) {
            case "time_asc":
                usort($participants, array($this,"orderByTime"));
                break;
            case "time_desc":
                usort($participants, array($this,"orderByTimeDesc"));
                break;
            case "bib_asc":
                usort($participants, array($this,"orderByBib"));
                break;
            case "bib_desc":
                usort($participants, array($this,"orderByBibDesc"));
                break;
            case "alpha_asc":
                usort($participants, array($this,"orderByName"));
                break;
            case "alpha_desc":
                usort($participants, array($this,"orderByNameDesc"));
                break;
                
        }

        return new ViewModel(
            array(
                "participants" => $participants,
                "event"        => $event,
                "event_gender" => "Classement ".$gender,
                "gender"       => $gender
            ),
        );  
    }


    public function selectRace($participants, $envent_id){
        $raceParticipants = array();
        foreach($participants as $participant){
            if($participant->getEvent()->getId() == $envent_id){
                array_push($raceParticipants,$participant);
            }
        }
        return $raceParticipants;
    }

    public function selectGender($participants, $gender){
        $genderParticipants = array();
        foreach($participants as $participant){
            if($participant->getSex() == $gender){
                array_push($genderParticipants,$participant);
            }
        }
        return $genderParticipants;
    }


    // order functions
    public function orderByTime($a, $b){
        if ($a->getTime() == $b->getTime()) {
            return 0;
        } else if ($a->getTime() < $b->getTime()) {
            return -1;
        } else {
            return 1;
        }
    }

    public function orderByTimeDesc($a, $b){
        if ($a->getTime() == $b->getTime()) {
            return 0;
        } else if ($a->getTime() > $b->getTime()) {
            return -1;
        } else {
            return 1;
        }
    }

    public function orderByBib($a, $b){
        if ($a->getBibNumber() == $b->getBibNumber()) {
            return 0;
        } else if ($a->getBibNumber() < $b->getBibNumber()) {
            return -1;
        } else {
            return 1;
        }
    }

    public function orderByBibDesc($a, $b){
        if ($a->getBibNumber() == $b->getBibNumber()) {
            return 0;
        } else if ($a->getBibNumber() > $b->getBibNumber()) {
            return -1;
        } else {
            return 1;
        }
    }

    public function orderByName($a, $b){
        if (($a->getLastName().$a->getFirstName()) == ($b->getLastName().$b->getFirstName())) {
            return 0;
        } else if (($a->getLastName().$a->getFirstName()) < ($b->getLastName().$b->getFirstName())) {
            return -1;
        } else {
            return 1;
        }
    }

    public function orderByNameDesc($a, $b){
        if (($a->getLastName().$a->getFirstName()) == ($b->getLastName().$b->getFirstName())) {
            return 0;
        } else if (($a->getLastName().$a->getFirstName()) > ($b->getLastName().$b->getFirstName())) {
            return -1;
        } else {
            return 1;
        }
    }

}
