<?php

namespace App\Controller;

use App\Entity\Users;
use App\Entity\Favoris;
use App\Entity\Comments;
use App\Entity\SkateParks;
use App\Form\SkateparkType;
use App\Form\CommentaireType;
use Doctrine\ORM\EntityManagerInterface;
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
        $skateparks = $reposkatepark->findBy(array(), array('createdAt' => 'desc'), 3, 0);

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
    public function showSkateParksRegion($region)
    {
        $repo = $this->getDoctrine()->getRepository(SkateParks::class);
        $skatepark = $repo->findBy(array('region' => $region), array('createdAt' => 'desc'));

        return $this->render('frontend/skateParksRegion.html.twig', [
            'skatepark' => $skatepark
        ]);
    }

    /**
     * @Route("/Skatepark/{id}", name="show_skatepark")
     */
    public function showSkateparks(Skateparks $skatepark, Request $request, EntityManagerInterface $manager)
    {
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

            return $this->redirectToRoute('show_skatepark', ['id' => $skatepark->getId()]);
        }

        return $this->render('frontend/showSkatepark.html.twig', [
            'skatepark' => $skatepark,
            'commentaireForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/Contact", name="Contact")
     */
    public function AddEditSkatepark(Request $request, EntityManagerInterface $manager)
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
 
             return $this->redirectToRoute('ArticlesGestion');
         }
         return $this->render('frontend/contact.html.twig', [
            /* on passe le formulaire pour la page  */
            'contact_form' => $contact_form->createView()
        ]);
    }

    /**
     * @Route("/Skatepark/{id}/favoris", name="Skatepark_favoris")
     */
    public function FavorisSkatepark(SkateParks $skatepark, EntityManagerInterface $manager)
    {
        $favoris = new Favoris();
        $favoris->addSkatepark($skatepark)
                ->setUsername($this->getUser());
    
        $manager->persist($favoris);
        $manager->flush($favoris);

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
            'user' => $User
        ]);
    }
    
}
