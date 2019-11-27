<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Repository\AbonnementRepository;
use App\Repository\ActualiteRepository;
use App\Repository\CommentaireRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class CommentaireController extends AbstractFOSRestController
{

    /**
     *@var ActualiteRepository
     * @var CommentaireRepository
     */
    private $actualiteRepository;
    private $userRepository;
    private $abonementRepository;
    private $commentaireRepository;

    public function __construct(ActualiteRepository $actualiteRepository, CommentaireRepository $commentaireRepository, UserRepository $userRepository, AbonnementRepository $abonementRepository, EntityManagerInterface $entityManager, SerializerInterface $serializer)
    {
        $this->actualiteRepository = $actualiteRepository;
        $this->userRepository = $userRepository;
        $this->abonementRepository = $abonementRepository;
        $this->commentaireRepository = $commentaireRepository;
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
    }



    /**
     * @param Request $request
     * @return |FOS|RestBundle|View|view
     */
    public function postCommentaireAction(Request $request){
        $content = $request->get('content');
        $username = $request->get('username');
        $acturef = $request->get('id');

        $user = $this->userRepository->findOneBy(array("username" => $username));
        $actu = $this->actualiteRepository->find($acturef);

        if(is_null($user) or is_null($actu))
            return $this->json([
                "message" => "User ou news does not exit"
            ], Response::HTTP_UNAUTHORIZED);
        else{
            if($content!=null){
                $comment = new Commentaire();
                $comment->setActuref($actu->getIdactu())
                    ->setContent($content)
                    ->setNumcomm(0)
                    ->setNumlike(0)
                    ->setTimepublish(date_create(date('Y-m-d\TH:i:sP')))
                    ->setUserref($user->getId())
                    ->setCommorder(1);

                $em=$this->getDoctrine()->getManager();
                $em->persist($comment);
                $em->flush();

                $actu->setNumcomm($actu->getNumcomm() + 1);
                $em=$this->getDoctrine()->getManager();
                $em->persist($actu);
                $em->flush();

                return $this->json($comment,  Response::HTTP_CREATED);
            }
        }
    }


    //id est l'idActu de l'actualite
    public function getCommentaireAction(int $id){
        $actu = $this->actualiteRepository->find($id);

        if(!is_null($actu)){
            $formated = [];
            $i=0;
            $comments = $this->commentaireRepository->findBy(array("acturef" => $actu->getIdactu()));
            foreach($comments as $comment):
                $publisher = $this->userRepository->find($comment->getUserref());
                $formated[$i] = [
                    "idComment" => $comment->getIdcomment(),
                    "acturef" => $comment->getActuref(),
                    "userref" => $publisher->getUsername(),
                    "content" =>$comment->getContent(),
                    "numComm" => $comment->getNumcomm(),
                    "numLike" => $comment->getNumlike(),
                    "time" => $comment->getTimepublish()->getTimestamp()
                ];
                $i++;
            endforeach;

            return $this->json($formated, Response::HTTP_OK);
        }
        else
            return $this->json([
                "message" => "News does not exist"
            ], Response::HTTP_NOT_FOUND);
    }



    public function deleteCommentaireAction(int $id){
        $comment = $this->commentaireRepository->find($id);

        if(is_null($comment))
            return $this->json([
                "message" => "Comment does not exist"
            ], Response::HTTP_NOT_FOUND);
        else{
            $em = $this->getDoctrine()->getManager();
            $em->remove($comment);
            $em->flush();

            return $this->json([], Response::HTTP_OK);
        }
    }

}
