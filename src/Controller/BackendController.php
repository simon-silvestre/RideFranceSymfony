<?php

namespace App\Controller;

use App\Entity\Users;
use App\Entity\Comments;
use App\Entity\SkateParks;
use App\Form\SkateparkType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BackendController extends AbstractController
{
    /**
     * @Route("/Utilisateurs", name="UsersGestion")
     */
    public function showUserGestion()
    {
        $repo = $this->getDoctrine()->getRepository(Users::class);
        $users = $repo->findAll();

        return $this->render('backend/userGestion.html.twig', [
            'Users' => $users
        ]);
    }

    /**
     * @Route("/Commentaires", name="CommentsGestion")
     */
    public function showCommentsGestion()
    {
        $repo = $this->getDoctrine()->getRepository(Comments::class);
        $commentaires = $repo->findAll();

        return $this->render('backend/commentManager.html.twig', [
            'commentaires' => $commentaires
        ]);
    }

    /**
     * @Route("/Articles", name="ArticlesGestion")
     */
    public function showArticlesGestion()
    {
        $repo = $this->getDoctrine()->getRepository(SkateParks::class);
        $skateparks = $repo->findAll();

        return $this->render('backend/skateparksManager.html.twig', [
            'skateparks' => $skateparks
        ]);
    }

    /**
     * @Route("/Articles/Ajouter", name="Skatepark_add")
     * @Route("/Articles/{id}/Editer", name="Skatepark_edit")
     */
    public function AddEditSkatepark(Skateparks $skatepark = null, Request $request, EntityManagerInterface $manager)
    {
        if(!$skatepark)
        {
            $skatepark = new SkateParks();
        }

         /* on relie les champs du formulaire a ceux de la bdd */
         $skatepark_form = $this->createForm(SkateparkType::class, $skatepark);

         /*  Analise la requete passer en parametre */
         $skatepark_form->handleRequest($request);
 
         if($skatepark_form->isSubmitted() && $skatepark_form->isValid())
         {
            if(!$skatepark->getId())
            {
                $skatepark->setCreationDate(new \DateTime());
            }
            $skatepark->setValidate('0');

            /* On envoi les données dans la bdd */
            $manager->persist($skatepark);
            $manager->flush();
 
             return $this->redirectToRoute('ArticlesGestion');
         }
         return $this->render('backend/addSkatepark.html.twig', [
            /* on passe le formulaire pour la page  */
            'skatepark_form' => $skatepark_form->createView()
        ]);
    }

    /**
     * @Route("/Skatepark/{id}/delete", name="Skatepark_delete")
     */
    public function DeleteSkatepark(SkateParks $skatepark)
    {
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($skatepark);
        $manager->flush();

        return $this->redirectToRoute('ArticlesGestion');
    }

}
