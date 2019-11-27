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

class RegistrationController extends AbstractFOSRestController
{

    /**
    *@var UserRepository
    */
    private $userRepository;

    public function __construct(UserRepository $userRepository, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $entityManager)
    {
      $this->userRepository = $userRepository;
      $this->passwordEncoder = $passwordEncoder;
      $this->entityManager = $entityManager;
    }

    /**
     * @Route("/register", name="register")
     * @param Request $request
     * @return |FOS|RestBundle|View|view
     * @throws \Exception
     */
    public function index(Request $request)
    {
      $username = $request->get('username');
      $phone = $request->get('phone');
      $password = "".$username."PyEPLe".$phone;
      $nom = $request->get('nom')." ".$request->get('prenom');
      $naissance = $request->get('naissance');
      $country = $request->get('country');
      $description = $request->get('description');
      $poste = $request->get('poste');
      $adresse = $request->get('adresse');
      $profilPic = $request->get('profilPic');

      $user = $this->userRepository->findOneBy([
        'username'=>$username,
      ]);

      if(!is_null($user)){
        return $this->json([
            'message' => 'User already exists'
        ], Response::HTTP_CONFLICT);
      }
      $user = $this->userRepository->findOneBy([
          'phone'=>$phone,
      ]);

      if(!is_null($user)){
           return $this->json([
               'message' => 'User already exists'
           ], Response::HTTP_CONFLICT);
      }

      $user = new User();
      $user->setUsername($username);
      $user->setPassword(
      $this->passwordEncoder->encodePassword($user, $password)
      );
      $user->setPhone($phone);
      $user->setRoles(['ROLE_USER']);
      $user->setNom($nom);
      $user->setCountry($country);
      $user->setDatedenaissance(date_create_from_format("d/m/Y", $naissance));
      $user->setDateinscript(date_create(date('Y-m-d\TH:i:sP')));
      $user->setLastconn(date_create(date('Y-m-d\TH:i:sP')));
      $user->setAdresse($adresse);
      $user->setPoste($poste);
      $user->setProfilpic($profilPic);
      $user->setDescription($description);
      $this->entityManager->persist($user);
      $this->entityManager->flush();

      return $this->json($user,  Response::HTTP_OK);
    }
}
