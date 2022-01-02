<?php

namespace Participant\Controller;

use Doctrine\ORM\EntityManager;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ParticipantController extends AbstractActionController
{

    /** @var EntityManager $entityManager */
    private $entityManager;
    private $formElementManager;

    public function __construct($entityManager, $formElementManager)
    {
        $this->entityManager = $entityManager;
        $this->formElementManager = $formElementManager;
    }

    public function indexAction()
    {
        return new ViewModel();
    }

    public function listAction()
    {

        $participants = $this->entityManager->getRepository('Application\Entity\Participant')->findAll();

        return new ViewModel(
            array(
                "participants" => $participants
            )
        );

    }

    public function participantFormAction(){

        /** @var \Zend\Form\Form $form */
        $form = $this->formElementManager->get('participant_form');

        $id = (int) $this->params()->fromRoute('id', 0);

        /** @var \Application\Entity\Participant $participant */
        if (0 !== $id) {
            try {
                $participant = $this->entityManager->getRepository('Application\Entity\Participant')->find($id);
                $form->bind($participant);
            } catch (\Exception $e) {
                return $this->redirect()->toRoute('participant/list');
            }
        }

        /** @var Request $request */
        $request = $this->getRequest();

        if (!$request->isPost()) {
            return ['form' => $form];
        }

        $form->setData($request->getPost());

        if (!$form->isValid()) {
            return ['form' => $form];
        }else{

            $participant = $form->getData();
            /** @var \Application\Entity\Event $event */

            $event = $this->entityManager->getRepository('Application\Entity\Event')->find($participant->getEvent());
            $participant->setEvent($event);

            $this->entityManager->persist($participant);
            $this->entityManager->flush();

            return $this->redirect()->toRoute('participant/list');

        }

    }

    public function generateBibNumbersAction(){

        // Browse the list of participants by ascending id (order of registration)
        // and define a bib number (From 1 to 100(number of registred participants))
        $participants = $this->entityManager->getRepository('Application\Entity\Participant')->findAll();
        usort($participants, array($this,"orderById"));

        $bibNumber = 1;
        foreach($participants as $p){
            $p->setBibNumber($bibNumber);
            $bibNumber++;
            $this->entityManager->persist($p);
            $this->entityManager->flush();
        }

        return $this->redirect()->toRoute('participant/list');

    }

    public function deleteAction(){

        $id = (int) $this->params()->fromRoute('id', 0);


        if (0 !== $id) {
            try {
                $participant = $this->entityManager->getRepository('Application\Entity\Participant')->find($id);
       
                $this->entityManager->remove($participant);
                $this->entityManager->flush();

            } catch (\Exception $e) {
                return $this->redirect()->toRoute('participant/list');
            }
        }
        return $this->redirect()->toRoute('participant/list');

    }

    public function orderById($a, $b){
        if ($a->getId() == $b->getId()) {
            return 0;
        } else if ($a->getId() < $b->getId()) {
            return -1;
        } else {
            return 1;
        }
    }
}
