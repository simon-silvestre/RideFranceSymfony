<?php

namespace App\Controller;

use App\Entity\Users;
use App\Entity\Favoris;
use App\Entity\Comments;
use App\Entity\SkateParks;
use App\Form\SkateparkType;
use App\Form\CommentaireType;
use App\Repository\CommentsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FrontendController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function showhome()
    {
        $users = $this->getUser();

        $reposkatepark = $this->getDoctrine()->getRepository(SkateParks::class);
        $skateparks = $reposkatepark->findBy(array('validate' => 0), array('createdAt' => 'desc'), 3, 0);

        if($this->getUser())
        {
            $repouser = $this->getDoctrine()->getRepository(Users::class);
            $user = $repouser->find($users);

            return $this->render('frontend/home.html.twig', [
                'skateparks' => $skateparks,
                'user' => $user
            ]);
        }

        return $this->render('frontend/home.html.twig', [
            'skateparks' => $skateparks
        ]);
    }

    /**
     * @Route("/SkateParksMap", name="SkateParksMap")
     */
    public function showSkateParksMap()
    {
        return $this->render('frontend/skateParkMap.html.twig');
    }

    /**
     * @Route("/SkateParksRegion/{region}", name="SkateParksRegion")
     */
    public function showSkateParksRegion($region, Request $request, PaginatorInterface $paginator)
    {
        $repo = $this->getDoctrine()->getRepository(SkateParks::class);
        $donnees = $repo->findBy(array('region' => $region), array('createdAt' => 'desc'));

        $skatepark = $paginator->paginate(
            $donnees,
            $request->query->getInt('page', 1),
            9
        );

        return $this->render('frontend/skateParksRegion.html.twig', [
            'skatepark' => $skatepark,
        ]);
    }

    /**
     * @Route("/Skatepark/{id}", name="show_skatepark")
     */
    public function showSkateparks(Skateparks $skatepark, Request $request, EntityManagerInterface $manager, CommentsRepository $commentsRepository)
    {
        $skateparkId = $skatepark->getId();
        $user = $this->getUser();

        $repo = $this->getDoctrine()->getRepository(Favoris::class);
        $favoris = $repo->findBy(array('skatepark' => $skateparkId, 'username' => $user));
        
        if($favoris == false)
        {
            $coeur = 'vide';
        }
        else
        {
            $coeur = 'plein';
        }

        $commentaire = new Comments();
        $form = $this->createForm(CommentaireType::class, $commentaire);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $commentaire->setCreatedAt(new \dateTime())
                        ->setSignaler(0)
                        ->setSkatepark($skatepark)
                        ->setUser($this->getUser());
            $manager->persist($commentaire);
            $manager->flush();
            $this->addFlash('success', 'Le commentaire à bien été ajouté');

            return $this->redirectToRoute('show_skatepark', ['id' => $skatepark->getId()]);
        }

        $repo = $this->getDoctrine()->getRepository(Comments::class);
        $comment = $repo->findBy(array('skatepark' => $skatepark));

        if($comment == null)
        {
            return $this->render('frontend/showSkatepark.html.twig', [
                'skatepark' => $skatepark,
                'commentaireForm' => $form->createView(),
                'coeur' => $coeur,
                'score_avg' => '0'
            ]);
        }
        else 
        {
            return $this->render('frontend/showSkatepark.html.twig', [
                'skatepark' => $skatepark,
                'commentaireForm' => $form->createView(),
                'coeur' => $coeur,
                'score_avg' => $commentsRepository->getAvg($skatepark)
            ]);
        }
    }

    /**
     * @Route("/Skatepark/{id}/Signaler", name="Commentaire_signaler")
     */
    public function SignalerComment(Comments $comment, EntityManagerInterface $manager)
    {
        $comment->setSignaler('1');
        $manager->persist($comment);
        $manager->flush();
        $this->addFlash('success', 'Le commentaire à bien été signalé');

        return $this->redirectToRoute('show_skatepark', ['id' => $comment->getSkatepark()->getId()]);
    }

    /**
     * @Route("/Contact", name="Contact")
     */
    public function ShowContact(Request $request, EntityManagerInterface $manager)
    {
        $skatepark = new SkateParks();

         /* on relie les champs du formulaire a ceux de la bdd */
         $contact_form = $this->createForm(SkateparkType::class, $skatepark);

         /*  Analise la requete passer en parametre */
         $contact_form->handleRequest($request);
 
         if($contact_form->isSubmitted() && $contact_form->isValid())
         {
            $skatepark->setCreatedAt(new \DateTime());
            $skatepark->setValidate('1');

            /* On envoi les données dans la bdd */
            $manager->persist($skatepark);
            $manager->flush();
            $this->addFlash('success', 'Le skatepark à bien été envoyé');
 
            return $this->redirectToRoute('Contact');
         }
         return $this->render('frontend/contact.html.twig', [
            /* on passe le formulaire pour la page  */
            'contact_form' => $contact_form->createView()
        ]);
    }

    /**
     * @Route("/Skatepark/{id}/favoris", name="Skatepark_favoris")
     */
    public function AdddFavoris(SkateParks $skatepark, EntityManagerInterface $manager)
    {
        $favoris = new Favoris();
        $favoris->setSkatepark($skatepark)
                ->setUsername($this->getUser());
    
        $manager->persist($favoris);
        $manager->flush($favoris);
        $this->addFlash('success', 'Le skatepark à bien été ajouté aux favoris');
      
        return $this->redirectToRoute('show_skatepark', ['id' => $skatepark->getId()]);
    }

        /**
     * @Route("/favoris", name="Skatepark_showFavoris")
     */
    public function ShowFavorisSkatepark()
    {
        $users = $this->getUser();

        $repo = $this->getDoctrine()->getRepository(Users::class);
        $User = $repo->find($users);

        return $this->render('frontend/favoris.html.twig',[
            'user' => $User,
        ]);
    }

    /**
     * @Route("/favoris/{id}/delete", name="Favoris_delete")
     */
    public function Favorisdelete(Favoris $favoris, EntityManagerInterface $manager)
    {
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($favoris);
        $manager->flush();
        $this->addFlash('success', 'Le skatepark à bien été supprimé de vos favoris');
      
        return $this->redirectToRoute('Skatepark_showFavoris');
    }
    
    
}
