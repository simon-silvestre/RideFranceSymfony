<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Users;
use App\Form\RegistrationType;

class SecurityController extends AbstractController
{
    /**
     * @Route("/connexion", name="security_connexion")
     */
    public function connexion()
    {
        $user = new Users();

        /* on relie les champs du formulaire a ceux de la bdd */
        $inscription_form = $this->createForm(RegistrationType::class, $user);

        return $this->render('security/connexion.html.twig', [
            'inscription_form' => $inscription_form->createView()
        ]);
    }
}
