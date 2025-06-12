<?php

namespace App\Controller;

use App\Entity\Abonnement;
use App\Repository\AbonnementRepository;
use App\Repository\HashtagRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class AbonementController extends AbstractFOSRestController
{


    /**
     *@var HashtagRepository
     *@var UserRepository
     *@var AbonnementRepository
     */
    private $hashtagRepository;
    private $userRepository;
    private $abonnementRepository;

    public function __construct(HashtagRepository $hashtagRepository, AbonnementRepository $abonnementRepository, UserRepository $userRepository, EntityManagerInterface $entityManager, SerializerInterface $serializer)
    {
        $this->hashtagRepository = $hashtagRepository;
        $this->userRepository = $userRepository;
        $this->abonnementRepository = $abonnementRepository;
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
    }


    /**
     * @param Request $request
     * @return |FOS|RestBundle|View|view
     */
    public function postAbonnementAction(Request $request){
        $header = $request->headers->get('Authorization');
        $username = json_decode(base64_decode(str_replace('_', '/', str_replace('-','+',explode('.', $header)[1]))));

        //$username = $request->get('username');
        $id = $request->get('idH');

        $user = $this->userRepository->findOneBy(array("username" => $username->username));
        $hashtag = $this->hashtagRepository->find($id);

        if(is_null($hashtag) OR is_null($user))
            return $this->json([
                "message" => "User or Hashtag does not exit"
            ], Response::HTTP_UNAUTHORIZED);
        else{
            $abn = $this->abonnementRepository->findOneBy(array("abonneref" => $user->getId(), "hashtagref" => $hashtag->getIdhashtag()));

            if(!is_null($abn))
                return $this->json([
                    "message" => "Already following"
                ], Response::HTTP_ALREADY_REPORTED);
            else{
                $abonement = new Abonnement();
                $abonement->setHashtagref($hashtag->getIdhashtag())
                    ->setAbonneref($user->getId());
                $em=$this->getDoctrine()->getManager();
                $em->persist($abonement);
                $em->flush();

                return $this->json([], Response::HTTP_OK);
            }
        }
    }


    /**
     * @param Request $request
     * @return |FOS|RestBundle|View|view
     */
    public function deleteAbonnementAction(Request $request){
        $header = $request->headers->get('Authorization');
        $username = json_decode(base64_decode(str_replace('_', '/', str_replace('-','+',explode('.', $header)[1]))));

        //$username = $request->get('username');
        $idH = $request->get('id');

        $user = $this->userRepository->findOneBy(array("username" => $username->username));
        $hashtag = $this->hashtagRepository->find($idH);

        if(is_null($hashtag) OR is_null($user))
            return $this->json([
                "message" => "User ou Hashtag does not exit"
            ], Response::HTTP_UNAUTHORIZED);
        else{
            $abn = $this->abonnementRepository->findOneBy(array("abonneref" => $user->getId(), "hashtagref" => $hashtag->getIdhashtag()));

            if(is_null($abn))
                return $this->json([
                    "message" => "Already delete"
                ], Response::HTTP_ALREADY_REPORTED);
            else{
                $abonement = $this->abonnementRepository->findOneBy(array("abonneref" => $user->getId(), "hashtagref" => $hashtag->getIdhashtag()));
                $em = $this->getDoctrine()->getManager();
                $em->remove($abonement);
                $em->flush();

                return $this->json([], Response::HTTP_OK);
            }
        }
    }
}
