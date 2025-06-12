<?php

namespace App\Controller;

use App\Entity\Code;
use App\Entity\User;
use App\Entity\Usertemp;
use App\Repository\CodeRepository;
use App\Repository\UserRepository;
use App\Repository\UserTempRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserTempController extends AbstractFOSRestController
{
    /**
     *@var UserTempRepository
     */
    private $userTempRepository;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     *@var UserRepository
     */
    private $userRepository;
    /**
     * @var CodeRepository
     */
    private $codeRepository;

    public function __construct(UserRepository $userRepository, UserTempRepository $userTempRepository, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $entityManager, CodeRepository $codeRepository)
    {
        $this->userTempRepository = $userTempRepository;
        $this->passwordEncoder = $passwordEncoder;
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->codeRepository = $codeRepository;
    }

    /**
     * @Route("/register/temp/1", name="register")
     * @param Request $request
     * @return |FOS|RestBundle|View|view
     * @throws \Exception
     */
    public function index(Request $request){
        $phone = $request->get('phone');
        //$uuid = $request->get('id');
        $nom = $request->get('nom')." ".$request->get('prenom');
        $naissance = $request->get('naissance');
        $country = $request->get('country');
        $adresse = $request->get('adresse');

        if(is_null($phone) OR is_null($nom) OR is_null($nom) OR is_null($country) OR is_null($adresse))
            return $this->json([
                'message' => 'Provide phone number, name, country and adress informations'
            ], Response::HTTP_FORBIDDEN);


        $user2 = $this->userRepository->findOneBy([
            'phone'=>$phone,
        ]);


        if(!is_null($user2))
            return $this->json([
                'message' => 'User already exists'
            ], Response::HTTP_CONFLICT);

        else{
            $user = new Usertemp();
            //$user->setUsername($username);
            /*$user->setPassword(
                $this->passwordEncoder->encodePassword($user, $password)
            );*/
            //$user->setSpecial($encryption);
            $user->setPhone($phone);
            $user->setRoles(['ROLE_USER']);
            $user->setNom($nom);
            $user->setCountry($country);
            $user->setDatedenaissance(date_create_from_format("d/m/Y", $naissance));
            $user->setDateinscript(date_create(date('Y-m-d\TH:i:sP')));
            $user->setLastconn(date_create(date('Y-m-d\TH:i:sP')));
            $user->setAdresse($adresse);
            /*$user->setPoste($poste);
            $user->setProfilpic($profilPic);
            $user->setDescription($description);*/

            $this->entityManager->persist($user);
            $this->entityManager->flush();


            $string = "";
            $chaine = "123456789";
            srand((double)microtime() * 1000000);
            for ($i = 0; $i < 6; $i++) {
                $string .= $chaine[rand() % strlen($chaine)];
            }

            $url = 'http://api.allmysms.com/http/9.0/sendSms/';
            $login = 'dolsom';    //votre identifant allmysms
            $apiKey = '3f5c7dd0ab020fb';    //votre mot de passe allmysms
            $message = 'Votre code est ' . $string;    //le message SMS, attention pas plus de 160 caractères
            $sender = 'Pyeple';  //l'expediteur, attention pas plus de 11 caractères alphanumériques
            $msisdn = $phone;    //numéro de téléphone du destinataire
            $smsData = "<DATA>
                               <MESSAGE><![CDATA[" . $message . "]]></MESSAGE>
                               <TPOA>" . $sender . "</TPOA>
                               <SMS>
                                  <MOBILEPHONE>" . $msisdn . "</MOBILEPHONE>
                               </SMS>
                            </DATA>";

            /*$fields = array(
                'login'    => urlencode($login),
                'apiKey'      => urlencode($apiKey),
                'smsData'       => urlencode($smsData),
            );

            $fieldsString = "";
            foreach($fields as $key=>$value) {
                $fieldsString .= $key.'='.$value.'&';
            }
            rtrim($fieldsString, '&');

            try {

                $ch = curl_init();
                curl_setopt($ch,CURLOPT_URL, $url);
                curl_setopt($ch,CURLOPT_POST, count($fields));
                curl_setopt($ch,CURLOPT_POSTFIELDS, $fieldsString);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                $result = curl_exec($ch);

                echo $result;
                echo "\n".$message;

                curl_close($ch);

            } catch (Exception $e) {
                echo 'Api allmysms injoignable ou trop longue a repondre ' . $e->getMessage();
            }*/

            $code = new Code();
            $code->setCode($string);
            $code->setPhone($phone);
            $code->setDate(date_create(date('Y-m-d\TH:i:sP')));

            $this->entityManager->persist($code);
            $this->entityManager->flush();

            //retour
            return $this->json([
                "message" => "Temp registration 1 OK. Code " . $string,
                "id"=>$user->getId()
            ], Response::HTTP_OK);

        }

    }




    /**
     * @Route("/register/temp/2", name="register2")
     * @param Request $request
     * @return |FOS|RestBundle|View|view
     * @throws \Exception
     */
    public function confirmCode(Request $request){
        $phone = $request->get('phone');
        $codesent = $request->get('code');
        $id = $request->get('id');

        if(is_null($phone) OR is_null($codesent) OR is_null($id))
            return $this->json([
                'message' => 'Provide phone number, SMS code and id given'
            ], Response::HTTP_FORBIDDEN);

        $codesave = $this->codeRepository->findOneBy([
            "phone"=>$phone
        ]);
        $user = $this->userTempRepository->findOneBy([
            "id"=>$id
        ]);

        if(is_null($codesent))
            return $this->json([
                'message' => 'No code found'
            ], Response::HTTP_NOT_FOUND);
        else{
            $interval = (int)$codesave->getDate()->diff(new DateTime())->format("%i");
            if ($interval < 4){
                if($codesent==$codesave->getCode()){
                    $user->setStatus(true);
                    $this->entityManager->persist($user);
                    $this->entityManager->flush();

                    $this->entityManager->remove($codesave);
                    $this->entityManager->flush();

                    //retour
                    return $this->json([
                        "message" => "Temp registration 2 OK.",
                        "id"=>$user->getId()
                    ], Response::HTTP_OK);
                }
            }
            else{
                $this->entityManager->remove($codesave);
                $this->entityManager->flush();


                //renvoyer le code
                $string = "";
                $chaine = "123456789";
                srand((double)microtime() * 1000000);
                for ($i = 0; $i < 6; $i++) {
                    $string .= $chaine[rand() % strlen($chaine)];
                }

                $url = 'http://api.allmysms.com/http/9.0/sendSms/';
                $login = 'dolsom';    //votre identifant allmysms
                $apiKey = '3f5c7dd0ab020fb';    //votre mot de passe allmysms
                $message = 'Votre code est ' . $string;    //le message SMS, attention pas plus de 160 caractères
                $sender = 'Pyeple';  //l'expediteur, attention pas plus de 11 caractères alphanumériques
                $msisdn = $phone;    //numéro de téléphone du destinataire
                $smsData = "<DATA>
                               <MESSAGE><![CDATA[" . $message . "]]></MESSAGE>
                               <TPOA>" . $sender . "</TPOA>
                               <SMS>
                                  <MOBILEPHONE>" . $msisdn . "</MOBILEPHONE>
                               </SMS>
                            </DATA>";

                /*$fields = array(
                    'login'    => urlencode($login),
                    'apiKey'      => urlencode($apiKey),
                    'smsData'       => urlencode($smsData),
                );

                $fieldsString = "";
                foreach($fields as $key=>$value) {
                    $fieldsString .= $key.'='.$value.'&';
                }
                rtrim($fieldsString, '&');

                try {

                    $ch = curl_init();
                    curl_setopt($ch,CURLOPT_URL, $url);
                    curl_setopt($ch,CURLOPT_POST, count($fields));
                    curl_setopt($ch,CURLOPT_POSTFIELDS, $fieldsString);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                    $result = curl_exec($ch);

                    echo $result;
                    echo "\n".$message;

                    curl_close($ch);

                } catch (Exception $e) {
                    echo 'Api allmysms injoignable ou trop longue a repondre ' . $e->getMessage();
                }*/

                $code = new Code();
                $code->setCode($string);
                $code->setPhone($phone);
                $code->setDate(date_create(date('Y-m-d\TH:i:sP')));

                $this->entityManager->persist($code);
                $this->entityManager->flush();

                //retour
                return $this->json([
                    "message" => "New code " . $string,
                    "id"=>$user->getId(),
                    "state"=>"Confirmation required"
                ], Response::HTTP_MOVED_PERMANENTLY);
            }
        }
    }




    /**
     * @Route("/register/temp/3", name="register3")
     * @param Request $request
     * @return |FOS|RestBundle|View|view
     * @throws \Exception
     */
    public function registration3(Request $request){
        $username = $request->get('username');
        $uuid = $request->get('uid');
        $profilPic = $request->get('profilPic');
        $id = $request->get('id');

        if(is_null($username) OR is_null($uuid) OR is_null($id) )
            return $this->json([
                'message' => 'Provide username, uuid and id given'
            ], Response::HTTP_FORBIDDEN);

        $user = $this->userTempRepository->findOneBy([
            "id"=>$id
        ]);

        //$password = substr($uuid, 0, 8)."-".$username.substr($uuid, 9, 4)."PyEPLe".substr($uuid, 14, 4).$user->getUsername().substr($uuid, 19, 4)."moSSi".substr($uuid, 24, 8);

        $ciphering = "AES-128-CTR";
        $options = 0;
        $encryption_iv = '1234567891011121';
        $encryption_key = "DragOnBallsSuperSayyansMigateno";
        $encryption = openssl_encrypt($uuid, $ciphering, $encryption_key, $options, $encryption_iv);

        if(!is_null($user)){
            $user->setUsername($username);
            /*$user->setPassword(
                $this->passwordEncoder->encodePassword($user, $password)
            );*/
            $user->setSpecial($encryption);
            if(!is_null($profilPic))
                $user->setProfilpic($profilPic);

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            //retour
            return $this->json([
                "message" => "Temp registration 3 OK",
                "id"=>$user->getId()
            ], Response::HTTP_OK);
        }
        else
            return $this->json([
                "message" => "user does not exist",
            ], Response::HTTP_NOT_FOUND);


    }


    /**
     * @Route("/register/temp/4", name="register4")
     * @param Request $request
     * @return |FOS|RestBundle|View|view
     * @throws \Exception
     */
    public function registration4(Request $request){
        $poste = $request->get('poste');
        $description = $request->get('description');
        $id = $request->get('id');

        $user = $this->userTempRepository->findOneBy([
            "id"=>$id
        ]);

        $user->setPoste($poste);
        $user->setDescription($description);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        //retour
        return $this->json([
            "message" => "Temp registration 4 OK",
            "id"=>$user->getId(),
            "state"=>"Next step: Registration"
        ], Response::HTTP_OK);
    }



    /**
     * @Route("/register/final", name="register_final")
     * @param Request $request
     * @return |FOS|RestBundle|View|view
     * @throws \Exception
     */
    public function registrationFinal(Request $request){
        $id = $request->get('id');
        $uuid = $request->get('special');
        $userTemp = $this->userTempRepository->findOneBy([
            "id"=>$id
        ]);

        $password = substr($uuid, 0, 8)."-".$userTemp->getUsername().substr($uuid, 9, 4)."PyEPLe".substr($uuid, 14, 4).$userTemp->getPhone().substr($uuid, 19, 4)."moSSi".substr($uuid, 24, 8);


        if(!is_null($userTemp)){
            $user = new User();
            $user->setUsername($userTemp->getUsername());
            $user->setPassword(
                $this->passwordEncoder->encodePassword($user, $password)
            );
            $user->setSpecial($userTemp->getSpecial());
            $user->setPhone($userTemp->getPhone());
            $user->setRoles(['ROLE_USER']);
            $user->setNom($userTemp->getNom());
            $user->setCountry($userTemp->getCountry());
            $user->setDatedenaissance($userTemp->getDatedenaissance());
            $user->setDateinscript(date_create(date('Y-m-d\TH:i:sP')));
            $user->setLastconn(date_create(date('Y-m-d\TH:i:sP')));
            $user->setAdresse($userTemp->getAdresse());
            $user->setPoste($userTemp->getPoste());
            $user->setProfilpic($userTemp->getProfilpic());
            $user->setDescription($userTemp->getDescription());
            $user->setAdresse($userTemp->getAdresse());

            //suppression du temp
            $this->entityManager->remove($userTemp);
            $this->entityManager->flush();

            //enregistrement du nouvel utilisateur
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            return $this->json([
                "message" => "Registration OK."
            ],  Response::HTTP_OK);
        }
    }


}
