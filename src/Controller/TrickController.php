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
     * @Route("/trick/{id}", name="trick_show")
     */
    public function show(int $id):Response
    {
        $trick = $this->repo->find($id);

        return $this->render('trick/show.html.twig',[
            'trick' => $trick,
        ]);
    }
}
