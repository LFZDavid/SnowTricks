<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Form\TrickType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TrickController extends AbstractController
{

    /**
     * @Route("/trick/create", name="trick_create")
     * @Route("/trick/{slug}/edit", name="trick_edit")
     */
    public function form(?Trick $trick, Request $request,  SluggerInterface $slugger, EntityManagerInterface $manager):Response
    {
        if(!$trick) {
            $trick = new Trick;
        }

        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $slug = (string) $slugger->slug((string) $trick->getName())->lower();
            $trick->setSlug($slug);
            
            $manager->persist($trick);
            $manager->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('trick/form.html.twig',[
            'trick' => $trick,
            'formTrick' => $form->createView(),
            'edit' => $trick->getId() !== null,
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
