<?php

namespace App\Controller;

use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TrickController extends AbstractController
{

    private $repo;

    public function __construct(TrickRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * @Route("/trick/{slug}", name="trick_show")
     */
    public function show(string $slug):Response
    {
        $trick = $this->repo->findOneBy(['slug' => $slug]);
        return $this->render('trick/show.html.twig',[
            'trick' => $trick,
        ]);
    }
}
