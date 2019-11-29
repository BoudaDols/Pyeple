<?php

namespace App\Controller;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractFOSRestController
{
    /**
     * @Route("/", name="default")
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $currentRoute = $request->attributes->get('_route');
        return $this->json([
            "link" => $this->get('router')->generate($currentRoute, array('slug' => $request), true)
        ], Response::HTTP_OK);
    }

}
