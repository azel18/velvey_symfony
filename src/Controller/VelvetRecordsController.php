<?php

namespace App\Controller;

use App\Repository\DiscRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VelvetRecordsController extends AbstractController
{
    #[Route('', name: 'app_velvet_records')]
    public function index(DiscRepository $discRepository): Response
    {

        $discs = $discRepository->findAll();

        //dd();

        return $this->render('velvet_records/index.html.twig', [
            'controller_name' => 'VelvetRecordsController',
            'discs' => $discs
        ]);
    }
}
