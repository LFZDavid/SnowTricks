<?php

namespace App\Controller;

use DateTime;
use App\Entity\Trick;
use App\Form\TrickType;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TrickController extends AbstractController
{

    /**
     * @Route("/trick/create", name="trick_create")
     */
    public function create(Request $request, SluggerInterface $slugger, EntityManagerInterface $manager, FileUploader $fileUploader): Response
    {
        $trick = new Trick;
        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $imgFiles */
            $imgFiles = $form->get('medias')->getData();
            foreach ($imgFiles as $imgFile) {
                if($file = $imgFile->getFile()) {
                    $imgFileName = $fileUploader->upload($file);
                    $imgFile->setUrl($imgFileName);
                }
            }
            
            $slug = $slugger->slug($trick->getName())->lower();
            $trick->setSlug($slug);
            
            $manager->persist($trick);
            $manager->flush();
            
            return $this->redirectToRoute('home');
        }
        
        return $this->render('trick/create.html.twig',[
            'formTrick' => $form->createView(),
        ]);
    }
        
    /**
     * @Route("/trick/{slug}/edit", name="trick_edit")
     */
    public function edit(Trick $trick, Request $request, SluggerInterface $slugger, EntityManagerInterface $manager, FileUploader $fileUploader): Response
    {
        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $imgFiles */
            $imgFiles = $form->get('medias')->getData();
            
            foreach ($imgFiles as $imgFile) {
                if($file = $imgFile->getFile()) {
                    $imgFileName = $fileUploader->upload($file);
                    $imgFile->setUrl($imgFileName);
                }
            }
            
            $trick->setUpdatedAt(new DateTime());
            $slug = $slugger->slug($trick->getName())->lower();
            $trick->setSlug($slug);
            
            $manager->persist($trick);
            $manager->flush();
            
            return $this->redirectToRoute('home');
        }

        return $this->render('trick/edit.html.twig',[
            'trick' => $trick,
            'formTrick' => $form->createView(),
        ]);
    }

    /**
     * @Route("/trick/{slug}", name="trick_show", methods="GET")
     */
    public function show(Trick $trick):Response
    {
        return $this->render('trick/show.html.twig',[
            'trick' => $trick,
        ]);
    }

}
