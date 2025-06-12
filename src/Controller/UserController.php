<?php

namespace App\Controller;

use App\Entity\Code;
use App\Repository\CodeRepository;
use App\Repository\NoteutilisateurRepository;
use App\Repository\UserRepository;
use App\Entity\User;
use DateTime;
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
    /**
     *@var NoteutilisateurRepository
     */
    private $noteutilisateurRepository;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var SerializerInterface
     */
    private $serializer;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;
    /**
     * @var CodeRepository
     */
    private $codeRepository;

    public function __construct(UserRepository $userRepository, NoteutilisateurRepository $noteutilisateurRepository, CodeRepository $codeRepository, EntityManagerInterface $entityManager, SerializerInterface $serializer, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->userRepository = $userRepository;
        $this->noteutilisateurRepository = $noteutilisateurRepository;
        $this->entityManager = $entityManager;
        $this->codeRepository = $codeRepository;
        $this->serializer = $serializer;
        $this->passwordEncoder = $passwordEncoder;
    }


    //Met à jour le profil
    /**
     * @return |FOS|RestBundle|View|view
     */
    public function updateUserAction(Request $request){
        $data = $request->getContent();
        //$username = $formData->get('username');
        $header = $request->headers->get('Authorization');
        $username = json_decode(base64_decode(str_replace('_', '/', str_replace('-','+',explode('.', $header)[1]))));

        $info = $this->serializer->deserialize($data, "App\Entity\User", 'json');

        $user = $this->userRepository->findOneBy(array('username'=>$username->username));

        if(is_null($user))
            return $this->json([
                'message' => 'User does not exist'
            ], Response::HTTP_NOT_FOUND);
        else{
            $user->setUsername($info->getUsername());


            $ciphering = "AES-128-CTR";
            $options = 0;

            $decryption_iv = '1234567891011121';
            $decryption_key = "DragOnBallsSuperSayyansMigateno";
            $decryption=openssl_decrypt ($user->getSpecial(), $ciphering, $decryption_key, $options, $decryption_iv);
            $password = substr($decryption, 0, 8)."-".$info->getUsername().substr($decryption, 9, 4)."PyEPLe".substr($decryption, 14, 4).$user->getPhone().substr($decryption, 19, 4)."moSSi".substr($decryption, 24, 8);


            $user->setPassword(
                $this->passwordEncoder->encodePassword($user, $password)
            );
            $user->setNom($info->getNom());
            $user->setCountry($info->getCountry());
            $user->setAdresse($info->getAdresse());
            $user->setPoste($info->getPoste());
            $user->setProfilpic($info->getProfilpic());
            $user->setDescription($info->getDescription());

            $this->entityManager->persist($user);
            $this->entityManager->flush();
            return $this->json([
                'message' => 'User updated'
            ], Response::HTTP_OK);

        }
    }

    /**
     * @Route("/username/test", name="test_username")
     * @param Request $request
     * @return |FOS|RestBundle|View|view
     */
    public function testUserName(Request $request){
        $pseudo = $request->get('pseudo');
        /*$header = $request->headers->get('Authorization');
        $pseudo = json_decode(base64_decode(str_replace('_', '/', str_replace('-','+',explode('.', $header)[1]))));*/

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
     * @Route("/phone/test", name="test_phone")
     * @param Request $request
     * @return |FOS|RestBundle|View|view
     */
    public function testUserPhone(Request $request){
        $phone = $request->get('phone');
        /*$header = $request->headers->get('Authorization');
        $pseudo = json_decode(base64_decode(str_replace('_', '/', str_replace('-','+',explode('.', $header)[1]))));*/

        if(is_null($phone)){
            return $this->json([], Response::HTTP_FORBIDDEN);
        }
        else{
            $user = $this->userRepository->findOneBy(array('phone' => $phone));

            if(is_null($user)){
                return $this->json([], Response::HTTP_OK);
            }
            else{
                return $this->json([
                    "message" => "Phone number already register. Login now"
                ], Response::HTTP_NOT_ACCEPTABLE);
            }
        }
    }




    /**
     * @param Request $formData
     * @return |FOS|RestBundle|View|view
     */
    public function changeUserNumberAction(Request $formData){
        //$username = $formData->get('username');
        $oldNumber = $formData->get('oldNumber');
        $newNumber = $formData->get('newNumber');
        $password = $formData->get('password');
        $special = $formData->get('id');

        $header = $formData->headers->get('Authorization');
        $username = json_decode(base64_decode(str_replace('_', '/', str_replace('-','+',explode('.', $header)[1]))));

        $user = $this->userRepository->findOneBy(array('username'=>$username->username));

        if(is_null($user)){
            return $this->json([
                "message" => "User does not exist"
            ], Response::HTTP_NOT_FOUND);
        }
        else{
            $ciphering = "AES-128-CTR";
            $options = 0;
            $decryption_iv = '1234567891011121';
            $decryption_key = "DragOnBallsSuperSayyansMigateno";
            $decryption=openssl_decrypt ($user->getSpecial(), $ciphering, $decryption_key, $options, $decryption_iv);
            if($special!=$decryption)
                return $this->json([
                    "message" => "Bad credentials. Verify your identity"
                ], Response::HTTP_UNAUTHORIZED);


            if($user->getPhone()!=$oldNumber){
                return $this->json([
                    "message" => "The number given is not your current number"
                ], Response::HTTP_NOT_FOUND);
            }
            else{
                $codeEx = $this->codeRepository->findOneBy([
                    'phone' => $newNumber
                ]);

                if(!is_null($codeEx)){
                    $interval = (int)$codeEx->getDate()->diff(new DateTime())->format("%i");
                    if ($interval < 4)
                        return $this->json([
                            'message' => 'Code already sent'
                        ], Response::HTTP_ALREADY_REPORTED);

                    else{
                        $this->entityManager->remove($codeEx);
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
                        $msisdn = $newNumber;    //numéro de téléphone du destinataire
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
                        $code->setPhone($newNumber);
                        $code->setDate(date_create(date('Y-m-d\TH:i:sP')));

                        $this->entityManager->persist($code);
                        $this->entityManager->flush();

                        //retour
                        return $this->json([
                            "message" => "New code " . $string
                        ], Response::HTTP_OK);
                    }
                }

                else{
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
                    $msisdn = $newNumber;    //numéro de téléphone du destinataire
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
                    $code->setPhone($newNumber);
                    $code->setDate(date_create(date('Y-m-d\TH:i:sP')));

                    $this->entityManager->persist($code);
                    $this->entityManager->flush();

                    //retour
                    return $this->json([
                        "message" => "New code " . $string . ". En attente de confirmation"
                    ], Response::HTTP_OK);
                }
            }
        }
    }


    /**
     * @param Request $request
     * @return |FOS|RestBundle|View|view
     * @throws \Exception
     */
    public function confirmChangeUserNumberAction(Request $request){
        $oldNumber = $request->get('oldNumber');
        $newNumber = $request->get('newNumber');
        $codeRec = $request->get('code');
        $special = $request->get('id');

        $header = $request->headers->get('Authorization');
        $username = json_decode(base64_decode(str_replace('_', '/', str_replace('-','+',explode('.', $header)[1]))));


        $user = $this->userRepository->findOneBy([
            'username' => $username->username
        ]);

        if(is_null($user))
            return $this->json([
                'message' => 'User does not exists.'
            ], Response::HTTP_NOT_FOUND);
        else{
            $ciphering = "AES-128-CTR";
            $options = 0;
            $decryption_iv = '1234567891011121';
            $decryption_key = "DragOnBallsSuperSayyansMigateno";
            $decryption=openssl_decrypt ($user->getSpecial(), $ciphering, $decryption_key, $options, $decryption_iv);
            if($special!=$decryption)
                return $this->json([
                    "message" => "Bad credentials. Verify your identity"
                ], Response::HTTP_UNAUTHORIZED);


            //$user->setPassword("");
            if($user->getPhone()==$oldNumber){
                $code = $this->codeRepository->findOneBy([
                    'phone'=>$newNumber
                ]);

                if(!is_null($code)){
                    if($code->getCode()==$codeRec) {
                        $password = substr($special, 0, 8)."-".$user->getUsername().substr($special, 9, 4)."PyEPLe".substr($special, 14, 4).$user->getPhone().substr($special, 19, 4)."moSSi".substr($special, 24, 8);

                        $user->setPhone($newNumber);
                        $user->setPassword(
                            $this->passwordEncoder->encodePassword($user, $password)
                        );
                        $em=$this->getDoctrine()->getManager();
                        $em->persist($user);
                        $em->flush();
                        $this->entityManager->remove($code);

                        $this->entityManager->flush();
                        return $this->json([
                            "message" => "Number has been changed : " .$newNumber
                        ], Response::HTTP_OK);
                    }
                    else
                        return $this->json([
                            "message" => "Wrong code"
                        ], Response::HTTP_MOVED_PERMANENTLY);
                }
                else
                    return $this->json([
                        'message' => 'No code found'
                    ], Response::HTTP_NOT_FOUND);
            }

            else
                return $this->json([
                    'message' => 'Please retry'
                ], Response::HTTP_FORBIDDEN);
        }
    }

    /**
     * @param Request $formData
     * @return |FOS|RestBundle|View|view
     */
    public function disconnectUserSessionAction(Request $formData){
        $header = $formData->headers->get('Authorization');
        $username = json_decode(base64_decode(str_replace('_', '/', str_replace('-','+',explode('.', $header)[1]))));

        $user = $this->userRepository->findOneBy(array('username'=>$username->username));

        if(is_null($user)){
            return $this->json([
                "message" => "User does not exist"
            ], Response::HTTP_UNAUTHORIZED);
        }

        else{
            $user->setLastconn(date_create(date('Y-m-d\TH:i:sP')));

            $em=$this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            
            return $this->json([], Response::HTTP_OK);
        }
    }




    /**
     * @param Request $request
     * @return |FOS|RestBundle|View|view
     */
    public function getContactAction(Request $request){
        $data = $request->getContent();
        $data=json_decode($data,true);
        $formatted = [];
        $i = 0;

        foreach ($data as $contact){
            $user = $this->userRepository->findOneBy(array("phone" => $contact['num']));
            $noteSocial = 0;
            $noteReussite = 0;
            if(!is_null($user)){
                $socialelements = $this->noteutilisateurRepository->findBy(['utilisateurref' => $user->getId(), 'typenote' => ['sociale']]);
                $reussiteelements = $this->noteutilisateurRepository->findBy(array('utilisateurref' => $user->getId(), 'typenote' => ['succes']));

                //calcul de moyenne social
                foreach ($socialelements as $socialelement){
                    if(count($socialelements)==0)
                        $noteSocial = 0;
                    else{
                        $noteSocial += $socialelement->getValeurnote();
                    }
                }
                if(count($socialelements)!=0)
                    $noteSocial /= count($socialelements);

                //calcul de moyenne reussite
                foreach ($reussiteelements as $reussiteelement){
                    if(count($reussiteelements)==0)
                        $noteReussite = 0;
                    else{
                        $noteReussite += $reussiteelement->getValeurnote();
                    }
                }
                if(count($reussiteelements)!=0)
                    $noteReussite /= count($reussiteelements);

                $formatted[$i] = [
                    "nom" => $contact['nom'],
                    "num" => $contact['num'],
                    "social" => $noteSocial,
                    "reussite" => $noteReussite,
                ];
                $i++;
            }
        }

        return $this->json($formatted, Response::HTTP_ACCEPTED);
    }
}
