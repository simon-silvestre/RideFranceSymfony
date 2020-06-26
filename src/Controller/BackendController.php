<?php

namespace App\Controller;

use App\Entity\Users;
use App\Entity\Comments;
use App\Entity\SkateParks;
use App\Form\SkateparkType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BackendController extends AbstractController
{
    /**
     * @Route("/admin/Utilisateurs", name="UsersGestion")
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
     * @Route("/admin/Commentaires", name="CommentsGestion")
     */
    public function showCommentsGestion(Request $request, PaginatorInterface $paginator)
    {
        $repo = $this->getDoctrine()->getRepository(Comments::class);
        $donnees = $repo->findBy(array(), array('createdAt' => 'desc'));

        $commentaires = $paginator->paginate(
            $donnees,
            $request->query->getInt('page', 1),
            4
        );

        return $this->render('backend/commentManager.html.twig', [
            'commentaires' => $commentaires
        ]);
    }

    /**
     * @Route("/admin/Commentaires/{id}/delete", name="Commentaire_delete")
     */
    public function DeleteCommentaire(Comments $comment)
    {
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($comment);
        $manager->flush();

        return $this->redirectToRoute('CommentsGestion');
    }

    /**
     * @Route("/admin/Commentaires/{id}/Approuver", name="Commentaire_approuver")
     */
    public function ApprouverCommentaire(Comments $comment, EntityManagerInterface $manager)
    {
        $comment->setSignaler('0');
        $manager->persist($comment);
        $manager->flush();

        return $this->redirectToRoute('CommentsGestion');
    }

    /**
     * @Route("/admin/Articles", name="ArticlesGestion")
     */
    public function showArticlesGestion(Request $request, PaginatorInterface $paginator)
    {
        $repo = $this->getDoctrine()->getRepository(SkateParks::class);
        $donnees = $repo->findAll();

        $skateparks = $paginator->paginate(
            $donnees,
            $request->query->getInt('page', 1),
            12
        );

        return $this->render('backend/skateparksManager.html.twig', [
            'skateparks' => $skateparks
        ]);
    }

    /**
     * @Route("/admin/Articles/Ajouter", name="Skatepark_add")
     * @Route("/admin/Articles/{id}/Editer", name="Skatepark_edit")
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
                $skatepark->setCreatedAt(new \DateTime());
                $skatepark->setValidate('0');
            }

            /* On envoi les données dans la bdd */
            $manager->persist($skatepark);
            $manager->flush();
 
             return $this->redirectToRoute('ArticlesGestion');
         }
         return $this->render('backend/addEditSkatepark.html.twig', [
            /* on passe le formulaire pour la page  */
            'skatepark_form' => $skatepark_form->createView()
        ]);
    }

    /**
     * @Route("/admin/Skatepark/{id}/delete", name="Skatepark_delete")
     */
    public function DeleteSkatepark(SkateParks $skatepark)
    {
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($skatepark);
        $manager->flush();

        return $this->redirectToRoute('ArticlesGestion');
    }

    /**
     * @Route("/admin/Skatepark/{id}/Valider", name="Skatepark_validation")
     */
    public function ValidationSkatepark(SkateParks $skatepark, EntityManagerInterface $manager)
    {
        $skatepark->setValidate('0');
        $manager->persist($skatepark);
        $manager->flush();

        return $this->redirectToRoute('ArticlesGestion');
    }

}
