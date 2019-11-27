<?php


namespace App\Server;


use App\Entity\User;
use FOS\RestBundle\Decoder\JsonDecoder;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Guard\JWTTokenAuthenticator;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
use Symfony\Component\Serializer\Encoder\JsonDecode;

class ActuMod implements MessageComponentInterface{
    private $clients;

    public function __construct(){
        $this->clients = new \SplObjectStorage();
    }

    public function onOpen(ConnectionInterface $conn){
        $this->clients->attach($conn);
        echo "Connection from ".$conn->resourceId." started \n";
    }

    public function onClose(ConnectionInterface $closedConnection){
        $this->clients->detach($closedConnection);
        echo "Connection ".$closedConnection->resourceId." has disconnected \n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e){
        $conn->send('An error has occurred: '.$e->getMessage());
        $conn->close();
    }


    function onMessage(ConnectionInterface $from, $msg){
        $msg = json_decode($msg);
        if(!isset($msg->action))
            $from->close();
        else{
            //login users
            if($msg->action=="login"){
                $curl = curl_init();

                curl_setopt_array($curl, array(
                    CURLOPT_PORT => "8000",
                    CURLOPT_URL => "http://127.0.0.1:8000/api/login_check",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_CUSTOMREQUEST => "GET",
                    CURLOPT_POSTFIELDS => '{"username":"'.$msg->username.'","password":"'.$msg->password.'"}',
                ));

                $response = curl_exec($curl);
                $err = curl_error($curl);

                curl_close($curl);

                if ($err) {
                    echo "cURL Error #:" . $err;
                } else {
                    $return = $response;
                    $return = json_decode($return);
                    if(isset($return->code) AND $return->code==401){
                        $from->send(json_encode($return));
                        $from->close();
                    }
                    else{
                        $from->send(json_encode($return));
                    }
                }
            }

            //get Actualite
            if($msg->action=="getConcernedActus"){
                $curl = curl_init();

                curl_setopt_array($curl, array(
                    CURLOPT_PORT => "8000",
                    CURLOPT_URL => "http://127.0.0.1:8000/api/user_actualites",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_CUSTOMREQUEST => "GET",
                    CURLOPT_POSTFIELDS => "",
                    CURLOPT_HTTPHEADER => array(
                        "Accept: */*",
                        "Authorization: Bearer ".$msg->token,
                        "Connection: keep-alive",
                        "Content-Type: application/json",
                        "Host: 127.0.0.1:8000",
                        "user: ".$msg->user
                    ),
                ));

                $response = curl_exec($curl);
                $err = curl_error($curl);

                curl_close($curl);

                if ($err) {
                    echo "cURL Error #:" . $err;
                } else {
                    $return = $response;
                    $return = json_decode($return);
                    if(isset($return->code) AND $return->code==401){
                        $from->send(json_encode($return));
                        $from->close();
                    }
                    else{
                        $from->send(json_encode($return));
                    }
                }
            }
            //get Message
        }
    }
}