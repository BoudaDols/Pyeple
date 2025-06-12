<?php

namespace App\Controller;

use App\Entity\Groupe;
use App\Entity\Userhasgroupe;
use App\Repository\GroupeRepository;
use App\Repository\UserhasgroupeRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class GroupeController extends AbstractFOSRestController
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
    public function postGroupeAction(Request $request){
        //$username = $request->get('username');
        $name = $request->get('name');
        $description = $request->get('description');
        $droit = $request->get('droit');
        $visibilite = $request->get('visibilite');

        $header = $request->headers->get('Authorization');
        $username = json_decode(base64_decode(str_replace('_', '/', str_replace('-','+',explode('.', $header)[1]))));


        $user = $this->userRepository->findOneBy(array("username" => $username->username));
        if(is_null($user))
            return $this->json([], Response::HTTP_UNAUTHORIZED);
        else{
            $groupe = new Groupe();
            $groupe->setCreatorref($user->getId())
                ->setNom($name)
                ->setVisibilitegrp($visibilite)
                ->setDescriptiongrp($description)
                ->setDroit($droit);
            $em=$this->getDoctrine()->getManager();
            $em->persist($groupe);
            $em->flush();

            $userHasGroup = new Userhasgroupe();
            $userHasGroup->setDroituser(array('admin'))
                ->setUserref($user->getId())
                ->setGrouperef($groupe->getIdgroup());

            $em=$this->getDoctrine()->getManager();
            $em->persist($userHasGroup);
            $em->flush();

            return $this->json($groupe, Response::HTTP_OK);
        }
    }


    /**
     * @param Request $formData
     * @return |FOS|RestBundle|View|view
     */
    public function postGroupeMembersAction(Request $request, Request $formData){
        $data = $request->getContent();
        $groupe = $formData->get('idG');
        //$username = $formData->get('username');
        $header = $formData->headers->get('Authorization');
        $username = json_decode(base64_decode(str_replace('_', '/', str_replace('-','+',explode('.', $header)[1]))));

        $test=json_decode($data,true);
        $memberIDs=$this->userRepository->findBy(array('username'=>$test['contact']));
        $admin = $this->userRepository->findOneBy(array("username" => $username->username));
        $realAdmin = $this->userhasgroupeRepository->createQueryBuilder('u')
            ->where('u.grouperef = ?1')
            ->andWhere('u.userref = ?2')
            ->andWhere('u.droituser = ?3')
            ->setParameter(1, $groupe)
            ->setParameter(2, $admin->getId())
            ->setParameter(3, array("admin"))
            ->getQuery()
            ->getResult();

        if($memberIDs==[] or is_null($realAdmin))
            return $this->json([
                "message" => "Error"
            ], Response::HTTP_FORBIDDEN);
        else{
            foreach($memberIDs as $memberID):
                $exist = $this->userhasgroupeRepository->findOneBy(array("userref" => $memberID->getId(), "grouperef" => $groupe));
                if(is_null($exist)){
                    $userHasGroup = new Userhasgroupe();
                    $userHasGroup->setGrouperef($groupe)
                        ->setUserref($memberID->getId())
                        ->setDroituser(array('member'));
                    $em=$this->getDoctrine()->getManager();
                    $em->persist($userHasGroup);
                    $em->flush();
                }
            endforeach;

            return $this->json([], Response::HTTP_OK);
        }
    }


    /**
     * @param Request $request
     * @return |FOS|RestBundle|View|view
     */
    public function patchGroupeAction(Request $request, int $id){
        $groupe = $this->groupeRepository->find($id);
        //$username = $request->get('username');
        $header = $request->headers->get('Authorization');
        $username = json_decode(base64_decode(str_replace('_', '/', str_replace('-','+',explode('.', $header)[1]))));

        $name = $request->get('name');
        $description = $request->get('description');
        $droit = $request->get('droit');
        $visibilite = $request->get('visibilite');

        $admin = $this->userRepository->findOneBy(array("username" => $username->username));
        $realAdmin = $this->userhasgroupeRepository->createQueryBuilder('u')
            ->where('u.grouperef=?1')
            ->andWhere('u.userref=?2')
            ->andWhere('u.droituser=?3')
            ->setParameter(1, $groupe->getIdgroup())
            ->setParameter(2, $admin->getId())
            ->setParameter(3, array("admin"))
            ->getQuery()
            ->getResult();

        if(is_null($admin) or $realAdmin==[])
            return $this->json([], Response::HTTP_FORBIDDEN);
        elseif($name==null OR  $description==null OR $visibilite==null Or $droit==null )
            return $this->json([], Response::HTTP_NOT_FOUND);
        else{
            $groupe->setDroit($droit)
                ->setDescriptiongrp($description)
                ->setVisibilitegrp($visibilite)
                ->setNom($name);
            $em=$this->getDoctrine()->getManager();
            $em->persist($groupe);
            $em->flush();

            return $this->json([], Response::HTTP_OK);
        }
    }

    /**
     * @return |FOS|RestBundle|View|view
     */
    public function deleteGroupeMemberAction(Request $request){
        $data = $request->getContent();
        $groupe = $request->get('idG');
        //$username = $request->get('username');
        $header = $request->headers->get('Authorization');
        $username = json_decode(base64_decode(str_replace('_', '/', str_replace('-','+',explode('.', $header)[1]))));

        $test=json_decode($data,true);
        $memberIDs=$this->userRepository->findBy(array('username'=>$test['contact']));
        $admin = $this->userRepository->findOneBy(array("username" => $username->username));
        $realAdmin = $this->userhasgroupeRepository->createQueryBuilder('u')
            ->where('u.grouperef=?1')
            ->andWhere('u.userref=?2')
            ->andWhere('u.droituser=?3')
            ->setParameter(1, $groupe)
            ->setParameter(2, $admin->getId())
            ->setParameter(3, array("admin"))
            ->getQuery()
            ->getResult();

        if(is_null($memberIDs))
            return $this->json([
                "message" => "Users do not exist"
            ], Response::HTTP_FORBIDDEN);
        elseif(is_null($realAdmin))
            return $this->json([
                "message" => "You are not able to delete members"
            ], Response::HTTP_FORBIDDEN);
        else{
            foreach($memberIDs as $memberID):
                $userHasGroup = $this->userhasgroupeRepository->findOneBy(array('userref' => $memberID->getId()));
                $em=$this->getDoctrine()->getManager();
                $em->remove($userHasGroup);
                $em->flush();
            endforeach;

            return $this->json([
                "message" => "@".$admin->getUsername()." vous a supprimer du groupe"
            ], Response::HTTP_OK);
        }
    }
}
