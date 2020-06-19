<?php

namespace App\Controller;

use App\Entity\Users;
use App\Entity\Comments;
use App\Entity\SkateParks;
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

}
