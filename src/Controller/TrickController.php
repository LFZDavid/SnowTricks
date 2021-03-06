<?php

namespace App\Controller;

use DateTime;
use App\Entity\Trick;
use App\Entity\Comment;
use App\Form\TrickType;
use App\Form\CommentType;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TrickController extends AbstractController
{
    const DEFAULT_PAGINATE_CMTS = 10;

    /**
     * @Route("/trick/create", name="trick_create")
     * @IsGranted("ROLE_USER")
     */
    public function create(Request $request, EntityManagerInterface $manager, FileUploader $fileUploader): Response
    {
        $trick = new Trick;
        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $imgFiles */
            $imgFiles = $form->get('medias')->getData();
            foreach ($imgFiles as $imgFile) {
                if ($file = $imgFile->getFile()) {
                    $imgFileName = $fileUploader->upload($file);
                    $imgFile->setUrl($imgFileName);
                }
            }
            $trick->setAuthor($this->getUser());

            $manager->persist($trick);
            $manager->flush();
            
            $this->addFlash('success', 'Nouveau trick ajouté avec succès!');
            return $this->redirectToRoute('home');
        }
        
        return $this->render('trick/create.html.twig', [
            'formTrick' => $form->createView(),
        ]);
    }
        
    /**
     * @Route("/trick/{slug}/edit", name="trick_edit")
     * @IsGranted("ROLE_USER")
     */
    public function edit(Trick $trick, Request $request, EntityManagerInterface $manager, FileUploader $fileUploader): Response
    {
        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $imgFiles */
            $imgFiles = $form->get('medias')->getData();
            
            foreach ($imgFiles as $imgFile) {
                if ($file = $imgFile->getFile()) {
                    $imgFileName = $fileUploader->upload($file);
                    $imgFile->setUrl($imgFileName);
                }
            }

            $manager->flush();
            $this->addFlash('success', 'Modifications enregistrées avec succès!');
            return $this->redirectToRoute('home');
        }

        return $this->render('trick/edit.html.twig', [
            'trick' => $trick,
            'formTrick' => $form->createView(),
        ]);
    }

    /**
     * @Route("/trick/{slug}/delete", name="trick_delete", methods="DELETE")
     * @IsGranted("ROLE_USER")
     */
    public function delete(Trick $trick,EntityManagerInterface $manager):Response
    {
        $manager->remove($trick);
        $manager->flush();
        $this->addFlash('danger', 'Le Trick à été supprimé!');
        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/trick/{slug}/{nb<\d+>}", name="trick_show", methods={"GET","POST"})
     */
    public function show(Trick $trick, int $nb = self::DEFAULT_PAGINATE_CMTS, Request $request, EntityManagerInterface $manager):Response
    {
        $comment = new Comment();
        $commentForm = $this->createForm(CommentType::class, $comment);
        
        $commentForm->handleRequest($request);
        if($commentForm->isSubmitted() && $commentForm->isValid()) {
            $comment->setAuthor($this->getUser());
            $trick->addComment($comment);

            $manager->persist($comment);
            $manager->flush();

            $this->addFlash('success', 'Votre commentaire à  été enregistré!');
            return $this->redirectToRoute('trick_show', ['slug'=> $trick->getSlug()]);
        }
        
        return $this->render('trick/show.html.twig', [
            'trick' => $trick,
            'commentPaginate' => $nb,
            'commentForm' => $commentForm->createView()
        ]);
    }
    
}
