<?php

namespace App\Controller;

use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    private $repo;

    public function __construct(TrickRepository $repo)
    {
        $this->repo = $repo;
    }
    /**
     * @Route("/{nb<\d+>}", name="home")
     */
    public function index(int $nb = 15): Response
    {
        $tricks = $this->repo->findBy(
            [],
            ["createdAt" => "DESC"],
            $nb
        );

        return $this->render('home/index.html.twig', [
            'tricks' => $tricks,
        ]);
    }
}
