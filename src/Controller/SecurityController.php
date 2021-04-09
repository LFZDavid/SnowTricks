<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\AccountType;
use App\Service\AccountValidator;
use App\Repository\UserRepository;
use App\Service\AvatarFileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/user/signup", name="signUp")
     * @Security("is_anonymous()", statusCode=403, message="Vous ne pouvez pas vous inscrire en étant connecté !")
     */
    public function create(Request $request, EntityManagerInterface $manager): Response
    {
        $user = new User;
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('home');
        }
        
        return $this->render('security/signUp.html.twig',[
            'signUpForm' => $form->createView(),
        ]);
        
    }

    /**
     * @Route("/signup_confirm", name="signup_confirm")
     */
    public function confirmCreation(Request $request, EntityManagerInterface $manager, UserRepository $userRepository):Response
    {
        $user = $userRepository->findOneBy(['token' => $request->query->get('token')]);

        if($user) {
            $user->setActive(true);
            $manager->flush();
        }

        return $this->redirectToRoute(('home'));
    }

    /**
     * @Route("/login", name="app_login")
     * @Security("is_anonymous()", statusCode=403, message="Vous êtes déjà connecté!")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('home');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/user/account", name="account")
     * @IsGranted("ROLE_USER")
     */
    public function edit(Request $request, EntityManagerInterface $manager, AvatarFileUploader $fileUploader): Response
    {
        $user = $this->getUser();
        
        $user->setConfirmPassword($user->getPassword());        
        $form = $this->createForm(AccountType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $imgFile = $form->get('avatar')->getData();
            if ($file = $form->get('avatar')->get('file')->getData()) {
                if($user->getAvatar()->getUrl()) {
                    $fileUploader->deleteAvatarFile($user->getAvatar());
                }
                $imgFileName = $fileUploader->upload($file);
                $imgFile->setUrl($imgFileName);
            }
            $manager->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('security/account.html.twig',[
            'accountForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/security/lost_pwd", name="lost_pwd")
     */
    public function lostPwd(Request $request, AccountValidator $accountValidator, UserRepository $userRepository):Response
    {
       $form = $this->createFormBuilder()
            ->add('username', TextType::class)
            ->getForm();

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $submitedName = $form->get('username')->getData();
            $user = $userRepository->findOneBy([
                'name' => $submitedName,
                'active' => true,
            ]);

            if(!$user) {
                // todo : message user doesn't exist or not active
            }
            
            if($user && $user->getEmail()) {
                $accountValidator->sendResetPwdMail($user);
                // todo : message mail sent
                return $this->redirectToRoute('app_login');
            }

        }

        return $this->render('security/lost_pwd.html.twig', [
            'lostPwdForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/security/reset_pwd", name="reset_pwd")
     */
    public function resetPwd(Request $request, UserRepository $userRepository):Response
    {
        return $this->redirectToRoute('app_login');
    }
}

