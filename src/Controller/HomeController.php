<?php

namespace App\Controller;

use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    const DEFAULT_PAGINATE = 15;

    /**
     * @Route("/{nb<\d+>}", name="home")
     */
    public function index(int $nb = self::DEFAULT_PAGINATE, TrickRepository $repo): Response
    {
        $tricks = $repo->findBy(
            [],
            [
                "updatedAt"=>"DESC",
                "createdAt" => "DESC"
            ],
            $nb
        );

        return $this->render('home/index.html.twig', [
            'tricks' => $tricks,
        ]);
    }
}
