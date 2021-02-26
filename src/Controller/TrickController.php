<?php

namespace App\Controller;

use App\Entity\Trick;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TrickController extends AbstractController
{

    /**
     * @Route("/trick/create", name="trick_form")
     */
    public function form(?Trick $trick):Response
    {
        return $this->render('trick/form.html.twig',[
            'trick' => $trick,
        ]);
    }
    
    /**
     * @Route("/trick/{slug}", name="trick_show")
     */
    public function show(Trick $trick):Response
    {
        return $this->render('trick/show.html.twig',[
            'trick' => $trick,
        ]);
    }

}
