<?php

namespace App\Controller;

use App\Entity\Users;
use App\Entity\Comments;
use App\Form\ProfilType;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/Connexion", name="security_connexion")
     */
    public function connexion(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder)
    {
        $user = new Users();

        /* on relie les champs du formulaire a ceux de la bdd */
        $inscription_form = $this->createForm(RegistrationType::class, $user);

        /*  Analise la requete passer en parametre */
        $inscription_form->handleRequest($request);

        if($inscription_form->isSubmitted() && $inscription_form->isValid())
        {
            /* On crypt le mot de passe */
            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash)
                 ->setAdmin(0);

            /* On envoi les données dans la bdd */
            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('security_connexion');
        }

        return $this->render('security/connexion.html.twig', [
            /* on passe le formulaire pour la page  */
            'inscription_form' => $inscription_form->createView()
        ]);
    }

   /**
     * @Route("/Login", name="security_Login")
     */
    public function Login() 
    {
        return $this->redirectToRoute('security_profil');
    }

    /**
     * @Route("/deconnexion", name="security_logout")
     */
    public function logout() {}

    /**
     * @Route("/profil/{view}", name="security_profil")
     */
    public function showProfil($view)
    {
        $users = $this->getUser();

        $repo = $this->getDoctrine()->getRepository(Users::class);
        $User = $repo->find($users);

        if($view == 'view')
        {
            $update = false;

            return $this->render('security/profil.html.twig', [
                'update' => $update,
                'users' => $User,
            ]);
        }
        elseif($view == 'edit')
        {
            $update = true;

            $users = new Users();
            $users->setNom($User->getNom())
                  ->setPrenom($User->getPrenom())
                  ->setEmail($User->getEmail())
                  ->setUsername($User->getUsername())
                  ->setImageprofil($User->getImageprofil());

            $form = $this->createForm(ProfilType::class, $users);

            return $this->render('security/profil.html.twig', [
                'update' => $update,
                'users' => $User,
                'profil_Form' => $form->createView()
            ]);
        }
    }

}
