<?php

namespace App\Controller;

use App\Entity\Actualite;
use App\Repository\AbonnementRepository;
use App\Repository\ActualiteRepository;
use App\Repository\HashtagRepository;
use App\Repository\LikeactuRepository;
use App\Repository\UserRepository;
use DateInterval;
use DateTime;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ActualiteController extends AbstractFOSRestController
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
     * @var AbonnementRepository
     */
    private $abonementRepository;
    /**
     * @var HashtagRepository
     */
    private $hashtagRepository;
    /**
     * @var LikeactuRepository
     */
    private $likeactuRepository;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var SerializerInterface
     */
    private $serializer;

    public function __construct(ActualiteRepository $actualiteRepository, LikeactuRepository $likeactuRepository, UserRepository $userRepository, HashtagRepository $hashtagRepository, AbonnementRepository $abonementRepository, EntityManagerInterface $entityManager, SerializerInterface $serializer)
    {
        $this->actualiteRepository = $actualiteRepository;
        $this->likeactuRepository = $likeactuRepository;
        $this->userRepository = $userRepository;
        $this->abonementRepository = $abonementRepository;
        $this->hashtagRepository = $hashtagRepository;
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
    }

    public function getActualitesAction(){
        $actualites = $this->actualiteRepository->findAll();
        $formatted = array();
        $i = 0;
        foreach($actualites as $actualite):
            //$likes = $this->likeactuRepository->findBy(array("acturef" => $actualite->getIdactu()));

            $formatted[$i]=array(
                "type"=> $actualite->getType()[0],
                "message"=> $actualite->getMessage(),
                "categorie"=> $actualite->getCategorie(),
                "content"=> $actualite->getContent(),
                "publisherref"=> $actualite->getPublisherref(),
                "time"=> $actualite->getTime()->getTimestamp(),
                "numLike" =>$actualite->getNumlike(),
            );
            $i++;
        endforeach;

        $formatted = json_encode($formatted, JSON_FORCE_OBJECT);

        $response=new Response($formatted);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    //Creation d'actualites
    /**
     * @param Request $request
     * @return |FOS|RestBundle|View|view
     */
    public function postActualiteAction(Request $request){
        $type = $request->get('type');
        $category = $request->get('category');
        $message = $request->get('message');
        $content = $request->get('content');
        //$publisherref = $request->get('publisherref');
        $publisherplace = $request->get('publisherplace');

        $header = $request->headers->get('Authorization');
        $username = json_decode(base64_decode(str_replace('_', '/', str_replace('-','+',explode('.', $header)[1]))));


        $publisher = $this->userRepository->findOneBy(array('username'=>$username->username));
        if(!is_null($publisher)){
            $actualite = new Actualite();
            $actualite->setCategorie($category)
                    ->setTime(date_create(date('Y-m-d\TH:i:sP')))
                    ->setMessage($message)
                    ->setNumcomm(0)
                    ->setNumlike(0)
                    ->setPublisherref($publisher->getId());
            if($content!=null)
                $actualite->setContent($content);
            if($type!=null)
                $actualite->setType(array($type));
            if($publisherplace!=null)
                $actualite->setPublishplace($publisherplace);

            $em=$this->getDoctrine()->getManager();
            $em->persist($actualite);
            $em->flush();

            return $this->json($actualite,  Response::HTTP_CREATED);
        }
        else{
            return $this->json([
                'message' => 'User does not exist'
            ], Response::HTTP_UNAUTHORIZED);
        }
    }

    //Consultation d'actualite
    public function getActualiteAction(int $id){
        $actualite = $this->actualiteRepository->find($id);
        if(!is_null($actualite)){
            $publisher = $this->userRepository->find($actualite->getPublisherref());
            //$likes = $this->likeactuRepository->findBy(array("acturef" => $actualite->getIdactu()));
            $formatted=[
                "type"=> $actualite->getType(),
                "message"=> $actualite->getMessage(),
                "categorie"=> $actualite->getCategorie(),
                "numLike" =>$actualite->getNumlike(),
                "content"=> $actualite->getContent(),
                "publisherref"=> $publisher->getUsername(),
                "time"=> $actualite->getTime()->getTimestamp()
            ];

            return $this->json($formatted, Response::HTTP_OK);
        }
        else
            return new Response('',Response::HTTP_NOT_FOUND);
    }

    //Modification d'actualite
    public function patchActualiteAction (Request $request){
        $data = $request->getContent();
        $infos = $this->serializer->deserialize($data,'array','json');
        $modifier = $this->getDoctrine()->getRepository('App:Utilisateur')->findOneBy(array(''));
    }

    /**
     * @return |FOS|RestBundle|View|view
     * @throws \Exception
     */
    public function pubActualiteAction(){
        $formatted = [];
        $aujourdhui = new DateTime();
        $sub30=$aujourdhui->sub(new DateInterval('P30D'));

        $pubs =$this->actualiteRepository->createQueryBuilder('m')
            ->where("m.time > ?1")
            ->andWhere("m.type='pub'")
            ->setParameter(1, $sub30)
            ->getQuery()
            ->getResult();

        $i=0;
        foreach($pubs as $pub):
            $publisher = $this->userRepository->find($pub->getPublisherref());
            //$likes = $this->likeactuRepository->findBy(array("acturef" => $pub->getIdactu()));

            $formatted["'".$i."'"]=[
                "id"=> $pub->getIdactu()[0],
                "type"=> $pub->getType(),
                "message"=> $pub->getMessage(),
                "categorie"=> $pub->getCategorie(),
                "content"=> $pub->getContent(),
                "numComm"=> $pub->getNumcomm(),
                "numLike"=> $pub->getNumlike(),
                "publishPlace"=> $pub->getPublishplace(),
                "publisherref"=>"".$publisher->getUsername(),
                "time"=> $pub->getTime()->getTimestamp()
            ];
            $i++;
        endforeach;

        return $this->json($formatted, Response::HTTP_OK);
    }

    /**
     * @return |FOS|RestBundle|View|view
     * @throws \Exception
     */
    public function linkedActualitesAction(Request $request){
        $data = $request->getContent();
        //$username = $formData->get('username');
        $test=json_decode($data,true);
        $formated=[];
        $aujourdhui = new DateTime();
        $sub30 = $aujourdhui->sub(new DateInterval('P30D'));

        $header = $request->headers->get('Authorization');
        $username = json_decode(base64_decode(str_replace('_', '/', str_replace('-','+',explode('.', $header)[1]))));


        $user = $this->userRepository->findOneBy(array("username" => $username->username));
        $abonements = $this->abonementRepository->findBy(array("abonneref" => $user->getId()));
        $publisherID=$this->userRepository->findBy(array('phone'=>$test['contact']));

        $expressions=[];
        if(is_null($abonements) and is_null($publisherID)){
            return $this->json([], Response::HTTP_NOT_FOUND);
        }
        else{
            $j=0;
            foreach($abonements as $abonement):
                $hashtag = $this->hashtagRepository->find($abonement->getHashtagref());
                $expressions[$j]="UPPER(m.categorie) = UPPER('".$hashtag->getCategoriehashtag()."')";
                $j++;
            endforeach;

            foreach($abonements as $abonement):
                $hashtag = $this->hashtagRepository->find($abonement->getHashtagref());
                $expressions[$j] = "m.message LIKE '#".$hashtag->getCategoriehashtag()."'";
                $j++;
            endforeach;

            foreach($publisherID as $id):
                $expressions[$j]="m.publisherref = ".$id->getId();
                $j++;
            endforeach;

            $actus=$this->actualiteRepository->createQueryBuilder('m')
                ->where("m.time > ?1")
                ->andWhere('m.type != ?2')
                ->andWhere(
                    new Expr\Orx($expressions)
                )
                ->setParameter(1, $sub30)
                ->setParameter(2, array('pub'))
                ->getQuery()
                ->getResult();

            $k=0;
            foreach($actus as $actu):
                $publisher = $this->userRepository->find($actu->getPublisherref());
                //$likes = $this->likeactuRepository->findBy(array("acturef" => $actu->getIdactu()));

                $formated[$k]=[
                    "id"=> $actu->getIdactu()[0],
                    "type"=> $actu->getType(),
                    "message"=> $actu->getMessage(),
                    "categorie"=> $actu->getCategorie(),
                    "content"=> $actu->getContent(),
                    "numComm"=> $actu->getNumcomm(),
                    "numLike"=> $actu->getNumlike(),
                    "publishPlace"=> $actu->getPublishplace(),
                    "publisherref"=>"".$publisher->getUsername(),
                    "time"=> $actu->getTime()->getTimestamp()
                ];
                $k++;
            endforeach;

            $formated = json_encode($formated, JSON_FORCE_OBJECT);

            $response=new Response($formated);
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }
    }

    /**
     * @param Request $formData
     * @return |FOS|RestBundle|View|view
     * @throws \Exception
     */
    public function othersActualitesAction(Request $request, Request $formData){
        $data = $request->getContent();
        //$username = $formData->get('username');
        $test=json_decode($data,true);
        $formated=[];
        $aujourdhui = new DateTime();
        $sub30 = $aujourdhui->sub(new DateInterval('P30D'));

        $header = $request->headers->get('Authorization');
        $username = json_decode(base64_decode(str_replace('_', '/', str_replace('-','+',explode('.', $header)[1]))));


        $user = $this->userRepository->findOneBy(array("username" => $username->username));
        $abonements = $this->abonementRepository->findBy(array("abonneref" => $user->getId()));
        //$publisherID=$this->userRepository->findBy(array('username'=>$test['contact']));

        $expressions=[];
        if(is_null($abonements) and empty($test['contact'])){
            return $this->json([], Response::HTTP_NOT_FOUND);
        }
        else{
            $j=0;
            foreach($abonements as $abonement):
                $expressions[$j] = "m.message REGEXP '".$abonement."'";
                $j++;
            endforeach;

            $actus=$this->actualiteRepository->createQueryBuilder('m')
                ->where("m.time > ?1")
                ->andWhere(
                    new Expr\Orx($expressions)
                )
                ->setParameter(1, $sub30)
                ->getQuery()
                ->getResult();

            $k=0;
            foreach($actus as $actu):
                $publisher = $this->userRepository->find($actu->getPublisherref());
                //$likes = $this->likeactuRepository->findBy(array("acturef" => $actu->getIdactu()));

                $formated[$k]=[
                    "id"=> $actu->getIdactu(),
                    "type"=> $actu->getType()[0],
                    "message"=> $actu->getMessage(),
                    "categorie"=> $actu->getCategorie(),
                    "content"=> $actu->getContent(),
                    "numComm"=> $actu->getNumcomm(),
                    "numLike"=> $actu->getNumlike(),
                    "publishPlace"=> $actu->getPublishplace(),
                    "publisherref"=>"".$publisher->getUsername(),
                    "time"=> $actu->getTime()->getTimestamp()
                ];
                $k++;
            endforeach;

            $formated = json_encode($formated, JSON_FORCE_OBJECT);

            $response=new Response($formated);
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }
    }
}
