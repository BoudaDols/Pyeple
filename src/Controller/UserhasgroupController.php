<?php

namespace App\Controller;

use App\Repository\GroupeRepository;
use App\Repository\UserhasgroupeRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class UserhasgroupController extends AbstractController
{
    /**
     * @var UserRepository
     * @var GroupeRepository
     * @var UserhasgroupeRepository
     */
    private $userRepository;
    private $groupeRepository;
    private $userhasgroupeRepository;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var SerializerInterface
     */
    private $serializer;

    public function __construct(UserRepository $userRepository, EntityManagerInterface $entityManager, SerializerInterface $serializer, GroupeRepository $groupeRepository, UserhasgroupeRepository $userhasgroupeRepository)
    {
        $this->userRepository = $userRepository;
        $this->groupeRepository = $groupeRepository;
        $this->userhasgroupeRepository = $userhasgroupeRepository;
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
    }


    /**
     * @param Request $request
     * @return |FOS|RestBundle|View|view
     */
    public function patchUserhasgroupeAction(Request $request){
        $idGroupe = $request->get('idGroupe');
        $member = $request->get('member');
        //$username = $request->get('username');
        $header = $request->headers->get('Authorization');
        $username = json_decode(base64_decode(str_replace('_', '/', str_replace('-','+',explode('.', $header)[1]))));

        $newDroit = $request->get('droit');

        $memberID=$this->userRepository->findOneBy(array('username' => $member));
        $admin = $this->userRepository->findOneBy(array("username" => $username->username));
        $realAdmin = $this->userhasgroupeRepository->createQueryBuilder('u')
            ->where('u.grouperef=?1')
            ->andWhere('u.userref=?2')
            ->andWhere('u.droituser=?3')
            ->setParameter(1, $idGroupe)
            ->setParameter(2, $admin->getId())
            ->setParameter(3, array("admin"))
            ->getQuery()
            ->getResult();

        if(is_null($memberID))
            return $this->json([
                "message" => "Users do not exist"
            ], Response::HTTP_FORBIDDEN);
        elseif(is_null($realAdmin))
            return $this->json([
                "message" => "You are not able to delete members"
            ], Response::HTTP_FORBIDDEN);
        else{
            $userHasGroup = $this->userhasgroupeRepository->findOneBy(array('userref' => $memberID->getId()));
            if(!is_null($userHasGroup)){
                $userHasGroup->setDroituser(array($newDroit));
                $em=$this->getDoctrine()->getManager();
                $em->persist($userHasGroup);
                $em->flush();

                return $this->json([
                    "message" => "@".$admin->getUsername()." vous à nommer ".$newDroit." du groupe"
                ], Response::HTTP_OK);
            }
            else
                return $this->json([], Response::HTTP_NOT_FOUND);
        }
    }
}
