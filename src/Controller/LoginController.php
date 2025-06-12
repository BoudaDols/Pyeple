<?php


namespace App\Controller;


use App\Entity\Code;
use App\Repository\CodeRepository;
use App\Repository\UserRepository;
use App\Entity\User;
use DateInterval;
use DateTime;
use http\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class LoginController extends AbstractFOSRestController
{

    /**
     *@var UserRepository
     */
    private $userRepository;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var CodeRepository
     */
    private $codeRepository;

    public function __construct(UserRepository $userRepository, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $entityManager, CodeRepository $codeRepository)
    {
        $this->userRepository = $userRepository;
        $this->codeRepository = $codeRepository;
        $this->passwordEncoder = $passwordEncoder;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/login", name="login")
     * @param Request $request
     * @return |FOS|RestBundle|View|view
     * @throws \Exception
     */
    public function index(Request $request){
        $phone = $request->get('phone');
        /*$header = $request->headers->get('Authorization');
        $username = json_decode(base64_decode(str_replace('_', '/', str_replace('-','+',explode('.', $header)[1]))));*/

        $user = $this->userRepository->findOneBy([
            'phone'=>$phone,
        ]);

        if(is_null($user))
            return $this->json([
                'message' => 'User does not exists.',
                //'autho' => $username->username
            ], Response::HTTP_NOT_FOUND);

        else {
            //verifier qu'un code n'a pas été créer
            $codeEx = $this->codeRepository->findOneBy([
                'phone' => $phone
            ]);

            if (!is_null($codeEx)) {
                $interval = (int)$codeEx->getDate()->diff(new DateTime())->format("%i");
                if ($interval < 4)
                    return $this->json([
                        'message' => 'Code already sent'
                    ], Response::HTTP_ALREADY_REPORTED);
                //si le temps depasse 4min
                else {
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
                        "message" => "New code " . $string
                    ], Response::HTTP_OK);
                }

            } //si aucun code trouvé
            else {
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
                    "message" => "Code " . $string
                ], Response::HTTP_OK);
            }
        }
    }


    /**
     * @Route("/login2", name="login_end")
     * @param Request $request
     * @return |FOS|RestBundle|View|view
     * @throws \Exception
     */
    public function confirmCode(Request $request){
        $phone = $request->get('phone');
        $codeRec = $request->get('code');
        $uuid = $request->get('id');

        $user = $this->userRepository->findOneBy([
            'phone'=>$phone,
        ]);

        if(is_null($user))
            return $this->json([
                'message' => 'User does not exists.'
            ], Response::HTTP_NOT_FOUND);
        else{
            //$user->setPassword("");
            $code = $this->codeRepository->findOneBy([
                'phone'=>$phone
            ]);

            if(!is_null($code)){

                if($code->getCode()==$codeRec) {
                    $encryption_iv = '1234567891011121';
                    $encryption_key = "DragOnBallsSuperSayyansMigateno";
                    $ciphering = "AES-128-CTR";
                    $options = 0;
                    $user->setSpecial(openssl_encrypt($uuid, $ciphering, $encryption_key, $options, $encryption_iv));
                    $this->entityManager->persist($user);

                    $formatted=[
                        "nom" => $user->getNom(),
                        "datedenaissance" => $user->getDatedenaissance(),
                        "country" => $user->getCountry(),
                        "username" => $user->getUsername(),
                        "special" =>  $uuid,
                        "phone" => $user->getPhone(),
                        "roles" => $user->getRoles(),
                        "poste" => $user->getPoste(),
                        "description" => $user->getDescription(),
                        "adresse" => $user->getAdresse(),
                        "profilpic" => $user->getProfilpic(),
                    ];

                    $this->entityManager->remove($code);


                    $this->entityManager->flush();


                    return $this->json($formatted, Response::HTTP_OK);
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
    }
}