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
    public function inscription(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder)
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
            $user->setPassword($hash);

            /* On envoi les données dans la bdd */
            $manager->persist($user);
            $manager->flush();
            $this->addFlash('success', 'Merci pour votre inscription ! Vous pouvez maintenant vous connectez');

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
        return $this->redirectToRoute('security_profil', array('view' => 'view'));
    }

    /**
     * @Route("/deconnexion", name="security_logout")
     */
    public function logout() {}

    /**
     * @Route("/profil/{view}", name="security_profil")
     */
    public function showProfil($view, Request $request, EntityManagerInterface $manager)
    {
        $users = $this->getUser();

        $repo = $this->getDoctrine()->getRepository(Users::class);
        $User = $repo->find($users);
        $update = false;

        if($view == 'edit')
        {
            $update = true;

            $profil_form = $this->createForm(ProfilType::class, $User);

            $profil_form->handleRequest($request);

            if($profil_form->isSubmitted() && $profil_form->isValid())
            {
                $manager->persist($User);
                $manager->flush();

                return $this->redirectToRoute('security_profil', array('view' => 'view'));
            }
            return $this->render('security/profil.html.twig', [
                'update' => $update,
                'users' => $User,
                'profil_Form' => $profil_form->createView()
            ]);
        }
        return $this->render('security/profil.html.twig', [
            'update' => $update,
            'users' => $User,
        ]);
    }

      /**
     * @Route("/profil/view/{id}/delete", name="UserComment_delete")
     */
    public function DeleteCommentaire(Comments $Comment)
    {
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($Comment);
        $manager->flush();
        $this->addFlash('success', 'Le commentaire à bien été supprimé');

       return $this->redirectToRoute('security_profil', array('view' => 'view'));
    }

}
