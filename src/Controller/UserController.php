<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;

class UserController extends AbstractFOSRestController
{

    /**
     *@var UserRepository
     */
    private $userRepository;

    public function __construct(UserRepository $userRepository, EntityManagerInterface $entityManager, SerializerInterface $serializer, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
        $this->passwordEncoder = $passwordEncoder;
    }


    //Met à jour le profil
    /**
     * @param Request $formData
     * @return |FOS|RestBundle|View|view
     */
    public function updateUserAction(Request $request, Request $formData){
        $data = $request->getContent();
        $username = $formData->get('username');
        $info = $this->serializer->deserialize($data, "App\Entity\User", 'json');

        $user = $this->userRepository->findOneBy(array('username'=>$username));

        if(is_null($user))
            return $this->json([
                'message' => 'User does not exist'
            ], Response::HTTP_NOT_FOUND);
        else{
            $user = $this->userRepository->findOneBy(array('username'=>$info->getUsername()));
            if(is_null($user)){
                $user->setDescription($info->getDescription());
                $user->setUsername($info->getUsername());
                $user->setCountry($info->getCountry());
                $user->setNom($info->getNom());
                $user->setPoste($info->getPoste());

                $this->entityManager->persist($user);
                $this->entityManager->flush();
                return $this->json([
                    'message' => 'User updated'
                ], Response::HTTP_OK);
            }
        }
    }

    /**
     * @param Request $request
     * @return |FOS|RestBundle|View|view
     */
    public function testUserNameAction(Request $request){
        $pseudo = $request->get('pseudo');
        if(is_null($pseudo)){
            return $this->json([], Response::HTTP_FORBIDDEN);
        }
        else{
            $user = $this->userRepository->findOneBy(array('username' => $pseudo));

            if(is_null($user)){
                return $this->json([], Response::HTTP_OK);
            }
            else{
                return $this->json([], Response::HTTP_NOT_ACCEPTABLE);
            }
        }
    }

    /**
     * @param Request $formData
     * @return |FOS|RestBundle|View|view
     */
    public function changeUserNumberAction(Request $formData){
        $username = $formData->get('username');
        $oldNumber = $formData->get('oldNumber');
        $newNumber = $formData->get('newNumber');

        $user = $this->userRepository->findOneBy(array('username'=>$username));

        if(is_null($user)){
            return $this->json([
                "message" => "User does not exist"
            ], Response::HTTP_UNAUTHORIZED);
        }
        else{
            if($user->getPhone()!=$oldNumber){
                return $this->json([
                    "message" => "The number given is not your current number"
                ], Response::HTTP_NOT_FOUND);
            }
            else{
                $user->setPhone($newNumber);
                $user->setPassword(
                    $this->passwordEncoder->encodePassword($user, "".$username."PyEPLe".$newNumber)
                );
                $em=$this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();

                return $this->json([], Response::HTTP_OK);
            }
        }
    }
}
