<?php

namespace App\Controller;

use App\Entity\Message;
use App\Repository\AbonnementRepository;
use App\Repository\ActualiteRepository;
use App\Repository\HashtagRepository;
use App\Repository\MessageRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class MessageController extends AbstractController
{
    /**
     *@var UserRepository
     */
    private $userRepository;
    private $messageRepository;

    public function __construct( UserRepository $userRepository, MessageRepository $messageRepository, EntityManagerInterface $entityManager, SerializerInterface $serializer)
    {
        $this->userRepository = $userRepository;
        $this->messageRepository = $messageRepository;
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
    }


    /**
     * @param Request $request
     * @return |FOS|RestBundle|View|view
     */
    public function postMessageAction(Request $request){
        $texte = $request->get('texte');
        $duration = $request->get('duration');
        $type = $request->get('type');
        $pieceJointe = $request->get('jointe');
        $username = $request->get('username');
        $receiver = $request->get('receiver');

        $user = $this->userRepository->findOneBy(array("username" => $username));
        $receiver = $this->userRepository->findOneBy(array("username" => $receiver));

        if(is_null($user) or is_null($receiver))
            return $this->json(["message" => "No user found for this username"], Response::HTTP_FORBIDDEN);
        else{
            if($texte=='' or $type=='')
                return $this->json(["message" => "Not enough data field"], Response::HTTP_FAILED_DEPENDENCY);
            else{
                $message = new Message();
                $message->setMessage($texte)
                        ->setType(array($type))
                        ->setReceiverref($receiver->getId())
                        ->setSenderref($user->getId())
                        ->setStatus(false)
                        ->setTime(date_create(date('Y-m-d\TH:i:sP')));
                if($pieceJointe != '')
                    $message->setPiecejointe($pieceJointe);
                if($type=='audio')
                    $message->setDuration($duration);

                $em=$this->getDoctrine()->getManager();
                $em->persist($message);
                $em->flush();

                return $this->json(["message" => "ok"], Response::HTTP_OK);
            }
        }
    }


    /**
     * @param Request $request
     * @return |FOS|RestBundle|View|view
     */
    public function getMessageByreceiverAction(Request $request){
        $username = $request->get('username');

        $user = $this->userRepository->findOneBy(array("username" => $username));

        if(is_null($user))
            return $this->json(["message" => "No user found for this username"], Response::HTTP_FORBIDDEN);
        else{
            $messages = $this->messageRepository->findBy(array("receiverref" => $user->getId()));
            $i=0;
            $formated = [];
            foreach($messages as $message):
                $sender = $this->userRepository->find($message->getReceiverref());

                $formated[$i] = [
                    "message" => $message->getMessage(),
                    "sender" => $sender->getUsername(),
                    "time" => $message->getTime()->getTimestamp(),
                    "type" => $message->getType(),
                    "duration" => $message->getDuration(),
                    "piece" => $message->getPiecejointe()
                ];
                $em=$this->getDoctrine()->getManager();
                $em->remove($message);
                $em->flush();
                $i++;
            endforeach;

            return $this->json($formated, Response::HTTP_OK);
        }
    }


    /**
     * @return |FOS|RestBundle|View|view
     */
    public function getMessageAction(int $id){
        $message = $this->messageRepository->find($id);
        if(is_null($message))
            return $this->json(["message" => "No message found"], Response::HTTP_NOT_FOUND);
        else
            return $this->json($message, Response::HTTP_OK);
    }


    /**
     * @return |FOS|RestBundle|View|view
     */
    public function deleteMessageAction(int $id){
        $message = $this->messageRepository->find($id);
        if(is_null($message))
            return $this->json(["message" => "no message found"], Response::HTTP_NOT_FOUND);
        else{
            $em=$this->getDoctrine()->getManager();
            $em->remove($message);
            $em->flush();

            return $this->json(["message" => "ok"], Response::HTTP_OK);
        }
    }


    /**
     * @param Request $request
     * @return |FOS|RestBundle|View|view
     */
    public function patchMessageAction(Request $request, int $id){
        $texte = $request->get('message');
        $username = $request->get('username');

        $user = $this->userRepository->findOneBy(array("username" => $username));
        $realUser = $this->messageRepository->findBy(array("senderref" => $user->getId()));

        if($realUser!=[]){
            $message = $this->messageRepository->find($id);
            if(is_null($message))
                return $this->json(["message" => "no message field"], Response::HTTP_NOT_FOUND);
            else{
                $message->setMessage($texte);
                $em=$this->getDoctrine()->getManager();
                $em->persist($message);
                $em->flush();

                return $this->json(["message" => "ok"], Response::HTTP_OK);
            }
        }
        else
            return $this->json(["message" => "Forbidden for this user"], Response::HTTP_FORBIDDEN);
    }
}
