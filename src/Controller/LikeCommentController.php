<?php

namespace App\Controller;

use App\Entity\Likeactu;
use App\Entity\Likecomment;
use App\Repository\AbonnementRepository;
use App\Repository\ActualiteRepository;
use App\Repository\CommentaireRepository;
use App\Repository\HashtagRepository;
use App\Repository\LikeactuRepository;
use App\Repository\LikecommentRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class LikeCommentController extends AbstractController
{
    /**
     *@var ActualiteRepository
     */
    private $actualiteRepository;
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var LikecommentRepository
     */
    private $likecommentRepository;
    /**
     * @var CommentaireRepository
     */
    private $commentaireRepository;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var SerializerInterface
     */
    private $serializer;

    public function __construct(ActualiteRepository $actualiteRepository, CommentaireRepository $commentaireRepository, LikecommentRepository $likecommentRepository, UserRepository $userRepository, EntityManagerInterface $entityManager, SerializerInterface $serializer)
    {
        $this->actualiteRepository = $actualiteRepository;
        $this->userRepository = $userRepository;
        $this->likecommentRepository = $likecommentRepository;
        $this->commentaireRepository = $commentaireRepository;
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
    }



    /**
     * @param Request $request
     * @return |FOS|RestBundle|View|view
     */
    public function postLikeCommentAction(Request $request){
        $idComment = $request->get('idComment');
        //$username = $request->get('username');
        $header = $request->headers->get('Authorization');
        $username = json_decode(base64_decode(str_replace('_', '/', str_replace('-','+',explode('.', $header)[1]))));


        $user = $this->userRepository->findOneBy(array("username" => $username->username));
        $comment = $this->commentaireRepository->find($idComment);

        if(is_null($comment) or is_null($user))
            return $this->json(["message" => "wrong"], Response::HTTP_FORBIDDEN);
        else{
            $exist = $this->likecommentRepository->findOneBy(array("userref" => $user->getId(), "commentref" => $comment->getIdcomment()));

            if($exist==[]){
                $like = new Likecomment();
                $like->setCommentref($comment->getIdcomment())
                    ->setUserref($user->getId());

                $em=$this->getDoctrine()->getManager();
                $em->persist($like);
                $em->flush();

                $comment->setNumlike($comment->getNumlike()+1);
                $em=$this->getDoctrine()->getManager();
                $em->persist($comment);
                $em->flush();

                return $this->json(["message" => "ok"], Response::HTTP_OK);
            }
            else
                return $this->json(["message" => "Already exist"], Response::HTTP_ALREADY_REPORTED);
        }
    }


    /**
     * @param Request $request
     * @return |FOS|RestBundle|View|view
     */
    public function deleteLikeCommentAction(Request $request){
        $idComment = $request->get('idComment');
        //$username = $request->get('username');
        $header = $request->headers->get('Authorization');
        $username = json_decode(base64_decode(str_replace('_', '/', str_replace('-','+',explode('.', $header)[1]))));


        $user = $this->userRepository->findOneBy(array("username" => $username->username));
        $comment = $this->commentaireRepository->find($idComment);

        if(is_null($comment) or is_null($user))
            return $this->json(["message" => "wrong"], Response::HTTP_FORBIDDEN);
        else{
            $exist = $this->likecommentRepository->findOneBy(array("userref" => $user->getId(), "commentref" => $comment->getIdcomment()));

            if($exist!=[]){
                $em=$this->getDoctrine()->getManager();
                $em->remove($exist);
                $em->flush();

                $comment->setNumlike($comment->getNumlike()-1);
                $em=$this->getDoctrine()->getManager();
                $em->persist($comment);
                $em->flush();

                return $this->json(["message" => "ok"], Response::HTTP_OK);
            }
            else
                return $this->json(["message" => "Already exist"], Response::HTTP_ALREADY_REPORTED);
        }
    }


    /**
     * @param Request $request
     * @return |FOS|RestBundle|View|view
     */
    public function getLikecommentAction(int $id){
        $likeComments = $this->likecommentRepository->findBy(array("commentref" => $id));

        return $this->json(["num" => count($likeComments)], Response::HTTP_OK);
    }
}
