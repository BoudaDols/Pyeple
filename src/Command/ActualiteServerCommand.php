<?php


namespace App\Command;


use App\Server\ActuMod;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ActualiteServerCommand extends Command{
    protected function configure(){
        $this
            ->setName('app:actu:run')
            ->setDescription('Start chat server');
    }

    protected function execute(InputInterface $input, OutputInterface $output){
        $server = IoServer::factory(
            new HttpServer(new WsServer(new ActuMod())),
            8880
        );
        $server->run();
    }
}