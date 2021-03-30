<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

use App\Repository\UserRepository;

class SecurityController extends AbstractController
{

    /**
     * @Route("/", name="home")
     */
    public function home(AuthenticationUtils $authenticationUtils): Response
    {
        return $this->render('base.html.twig');
    }

    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        if($this->getUser()==NULL) return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
        if(in_array("ROLE_ADMIN", $this->getUser()->getRoles())) {
            return $this->redirectToRoute('admin');
        } else {
            //KEY XRzakcjhzwZhbX6p
            var_dump($this->getUser());
            //return $this->redirectToRoute('admin');
            //RETURN JWT
            //return $this->render('security/admin.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
        }
    }

    /**
     * @Route("/admin", name="admin")
     */
    public function admin(AuthenticationUtils $authenticationUtils, UserRepository $userRepository): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        $users = $userRepository->findAll();
        return $this->render('security/admin.html.twig', ['last_username' => $lastUsername, 'error' => $error, 'users'=>$users]);
    }

    /**
     * @Route("/admin/userupd", name="user_upd")
     */
    public function updateUser(Request $request, UserRepository $userRepository): Response
    {
        $data = $request->request;
        $userRepository->updateUserR($data);
        $this->addFlash('info', 'user succesfull updated !');
        return $this->redirectToRoute('admin');
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
