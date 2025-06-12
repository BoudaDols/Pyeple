<?php

namespace App\Controller;

use App\Entity\Abonnement;
use App\Entity\Hashtag;
use App\Repository\AbonnementRepository;
use App\Repository\HashtagRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class HashtagController extends AbstractFOSRestController
{


    /**
     *@var HashtagRepository
     *@var UserRepository
     *@var AbonnementRepository
     */
    private $hashtagRepository;
    private $userRepository;
    private $abonnementRepository;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var SerializerInterface
     */
    private $serializer;

    public function __construct(HashtagRepository $hashtagRepository, AbonnementRepository $abonnementRepository, UserRepository $userRepository, EntityManagerInterface $entityManager, SerializerInterface $serializer)
    {
        $this->hashtagRepository = $hashtagRepository;
        $this->userRepository = $userRepository;
        $this->abonnementRepository = $abonnementRepository;
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
    }


    public function getHashtagsAction(){
        $hashtags = $this->hashtagRepository->findBy(array('etathashtag'=>true));
        $formatted = [];
        $i = 0;
        foreach($hashtags as $hashtag):
            $creator = $this->userRepository->find($hashtag->getCreatorref());
            $formatted[$i]=[
                "nom" => $hashtag->getNomhashtag(),
                "categorie" => $hashtag->getCategoriehashtag(),
                "description" => $hashtag->getDescriptionhashtgag(),
                "timeCreated" => $hashtag->getTimecreated()->getTimestamp(),
                "creator"  => $creator->getUsername(),
            ];
            $i++;
        endforeach;

        $data = $this->serializer->serialize($formatted,'json');
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @param Request $request
     * @return |FOS|RestBundle|View|view
     */
    public function postHashtagAction(Request $request){
        $nom = $request->get('name');
        $categorie = $request->get('category');
        //$creator = $request->get('username');
        $header = $request->headers->get('Authorization');
        $creator = json_decode(base64_decode(str_replace('_', '/', str_replace('-','+',explode('.', $header)[1]))));

        $creator = $this->userRepository->findOneBy(array('username'=> $creator->username));
        $description = $request->get('description');

        $hashtag = new Hashtag();
        if(!is_null($categorie))
            $hashtag->setCategoriehashtag($categorie);
        if(!is_null($description))
            $hashtag->setDescriptionhashtgag($description);
        if(!is_null($creator))
            $hashtag->setCreatorref($creator->getId());
        $hashtag->setEtathashtag(true);
        $hashtag->setTimecreated(date_create(date('Y-m-d\TH:i:sP')));
        if(!is_null($nom))
            $hashtag->setNomhashtag($nom);

        $em=$this->getDoctrine()->getManager();
        $em->persist($hashtag);
        $em->flush();

        $abonement = new Abonnement();
        $abonement->setAbonneref($creator->getId())
                    ->setHashtagref($hashtag->getIdhashtag());

        $em=$this->getDoctrine()->getManager();
        $em->persist($abonement);
        $em->flush();

        return $this->json($hashtag,  Response::HTTP_CREATED);
    }



    public function getHashtagAction(int $id){
        $hashtag = $this->hashtagRepository->find($id);

        if(is_null($hashtag))
            return $this->json([
                "message" => "Hashtag does not exist"
            ], Response::HTTP_NOT_FOUND);
        else{
            $creator = $this->userRepository->find($hashtag->getCreatorref());
            $formatted=[
                "name" => $hashtag->getNomhashtag(),
                "category" =>$hashtag->getCategoriehashtag(),
                "description" => $hashtag->getDescriptionhashtgag(),
                "timeCreated" => $hashtag->getTimecreated(),
                "creator" => $creator->getUsername(),
            ];
            $hashtag = $this->serializer->serialize($formatted,'json');
            $response=new Response($hashtag);
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }
    }


    /**
     * @param Request $request
     * @return |FOS|RestBundle|View|view
     */
    public function bynameHashtagAction(Request $request){
        $nom = $request->get('nom');
        $hashtags = $this->hashtagRepository->createQueryBuilder('m')
                                            ->where("m.etathashtag=1")
                                            ->andWhere("m.nomhashtag LIKE ?1")
                                            ->setParameter(1, '%'.$nom.'%')
                                            ->getQuery()
                                            ->getResult();

        if (is_null($hashtags))
            return $this->json([
                "message" => "Hashtag does not exist"
            ], Response::HTTP_NOT_FOUND);
        else {
            $formatted = [];
            $i = 0;

            foreach ($hashtags as $hashtag):
                $creator = $this->userRepository->find($hashtag->getCreatorref());
                $formatted[$i] = [
                    "name" => $hashtag->getNomhashtag(),
                    "category" => $hashtag->getCategoriehashtag(),
                    "description" => $hashtag->getDescriptionhashtgag(),
                    "timeCreated" => $hashtag->getTimecreated(),
                    "creator" => $creator->getUsername(),
                ];
                $i++;
            endforeach;
            $hashtags = $this->serializer->serialize($formatted, 'json');
            $response = new Response($hashtags);
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }
    }



    /**
     * @param Request $request
     * @return |FOS|RestBundle|View|view
     */
    public function linkedHashtagAction(Request $request){
        //$username = $request->get('username');
        $header = $request->headers->get('Authorization');
        $username = json_decode(base64_decode(str_replace('_', '/', str_replace('-','+',explode('.', $header)[1]))));

        $user = $this->userRepository->findOneBy(array("username" => $username->username));
        if(is_null($user))
            return $this->json([
                "message" => "User does not exist"
            ], Response::HTTP_FORBIDDEN);
        else{
            $abonements = $this->abonnementRepository->findBy(array("abonneref" => $user->getId()));

            $formated = [];
            $i=0;
            foreach($abonements as $abonement):
                $hashtag = $this->hashtagRepository->find($abonement->getHashtagref());
                if(!is_null($hashtag)){
                    if($hashtag->getEtathashtag()) {
                        $creator = $this->userRepository->find($hashtag->getCreatorref());
                        $formated[$i] = [
                            "name" => $hashtag->getNomhashtag(),
                            "category" =>$hashtag->getCategoriehashtag(),
                            "description" =>$hashtag->getDescriptionhashtgag(),
                            "timecreated" =>$hashtag->getTimecreated()->getTimestamp(),
                            "creatorref" =>$creator->getUsername()
                        ];
                    }
                }
                $i++;
            endforeach;

            $hashtags = $this->serializer->serialize($formated, 'json');
            $response = new Response($hashtags);
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }
    }



    /**
     * @param Request $request
     * @return |FOS|RestBundle|View|view
     */
    public function othersHashtagAction(Request $request){
        //$username = $request->get('username');
        $header = $request->headers->get('Authorization');
        $username = json_decode(base64_decode(str_replace('_', '/', str_replace('-','+',explode('.', $header)[1]))));

        $user = $this->userRepository->findOneBy(array("username" => $username->username));
        if (is_null($user))
            return $this->json([
                "message" => "User does not exist"
            ], Response::HTTP_FORBIDDEN);
        else {
            $abonements = $this->abonnementRepository->findBy(array("abonneref" => $user->getId()));

            $formated = [];
            $i = 0;
            foreach ($abonements as $abonement):
                $hashtag = $this->hashtagRepository->find($abonement->getHashtagref());
                if (!is_null($hashtag)) {
                    if ($hashtag->getEtathashtag()) {
                        $others = $this->hashtagRepository->createQueryBuilder('h')
                            ->where('h.idhashtag!= '.$abonement->getHashtagref())
                            ->andWhere("h.categoriehashtag = '".$hashtag->getCategoriehashtag()."'")
                            ->getQuery()
                            ->getResult();
                        foreach ($others as $other):
                            $creator = $this->userRepository->find($other->getCreatorref());
                            $formated[$i] = [
                                "name" => $other->getNomhashtag(),
                                "category" =>$other->getCategoriehashtag(),
                                "description" =>$other->getDescriptionhashtgag(),
                                "timecreated" =>$other->getTimecreated()->getTimestamp(),
                                "creatorref" =>$creator->getUsername()
                            ];
                            $i++;
                        endforeach;
                    }
                }
            endforeach;

            $hashtags = $this->serializer->serialize($formated, 'json');
            $response = new Response($hashtags);
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }
    }
}
