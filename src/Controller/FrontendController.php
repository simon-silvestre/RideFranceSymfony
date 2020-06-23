<?php

namespace App\Controller;

use App\Entity\Users;
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
        $repo = $this->getDoctrine()->getRepository(SkateParks::class);
        $skateparks = $repo->findBy(array(), array('createdAt' => 'desc'), 3, 0);

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
                        ->setUser($this->getUser())
                        ->setUsername($this->getUser()->getUsername());
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
    
}
