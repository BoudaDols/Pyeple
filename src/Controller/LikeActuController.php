<?php

namespace App\Controller;

use App\Entity\Likeactu;
use App\Repository\AbonnementRepository;
use App\Repository\ActualiteRepository;
use App\Repository\HashtagRepository;
use App\Repository\LikeactuRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class LikeActuController extends AbstractController
{
    /**
     *@var ActualiteRepository
     */
    private $actualiteRepository;
    private $userRepository;
    private $abonementRepository;
    private $hashtagRepository;
    private $likeactuRepository;

    public function __construct(ActualiteRepository $actualiteRepository, LikeactuRepository $likeactuRepository, UserRepository $userRepository, HashtagRepository $hashtagRepository, AbonnementRepository $abonementRepository, EntityManagerInterface $entityManager, SerializerInterface $serializer)
    {
        $this->actualiteRepository = $actualiteRepository;
        $this->userRepository = $userRepository;
        $this->abonementRepository = $abonementRepository;
        $this->likeactuRepository = $likeactuRepository;
        $this->hashtagRepository = $hashtagRepository;
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
    }


    /**
     * @param Request $request
     * @return |FOS|RestBundle|View|view
     */
    public function postLikeActuAction(Request $request){
        $idActu = $request->get('idActu');
        $username = $request->get('username');

        $user = $this->userRepository->findOneBy(array("username" => $username));
        $actu = $this->actualiteRepository->find($idActu);

        if(is_null($actu) or is_null($user))
            return $this->json(["message" => "wrong"], Response::HTTP_FORBIDDEN);
        else{
            $exist = $this->likeactuRepository->findBy(array("userref" => $user->getId(), "acturef" => $actu->getIdactu()));
            if($exist==[]){
                $like = new Likeactu();
                $like->setUserref($user->getId())
                ->setActuref($idActu);

                $em=$this->getDoctrine()->getManager();
                $em->persist($like);
                $em->flush();

                $actu->setNumlike($actu->getNumlike()+1);
                $em=$this->getDoctrine()->getManager();
                $em->persist($actu);
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
    public function deleteLikeActuAction(Request $request){
        $idActu = $request->get('idActu');
        $username = $request->get('username');

        $user = $this->userRepository->findOneBy(array("username" => $username));
        $actu = $this->actualiteRepository->find($idActu);

        if(is_null($actu) or is_null($user))
            return $this->json(["message" => "wrong"], Response::HTTP_FORBIDDEN);
        else{
            $exist = $this->likeactuRepository->findOneBy(array("userref" => $user->getId(), "acturef" => $actu->getIdactu()));
            if(!is_null($exist)){
                $em=$this->getDoctrine()->getManager();
                $em->remove($exist);
                $em->flush();

                $actu->setNumlike($actu->getNumlike() - 1);
                $em=$this->getDoctrine()->getManager();
                $em->persist($actu);
                $em->flush();

                return $this->json(["message" => "ok"], Response::HTTP_OK);
            }
            else
                return $this->json(["message" => "Already deleted"], Response::HTTP_ALREADY_REPORTED);
        }
    }
}
