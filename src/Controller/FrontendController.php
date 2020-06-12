<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\SkateParks;

class FrontendController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home()
    {
        $repo = $this->getDoctrine()->getRepository(SkateParks::class);
        $skateparks = $repo->findAll();

        return $this->render('frontend/home.html.twig', [
            'skateparks' => $skateparks
        ]);
    }

     /**
     * @Route("/skatepark/{id}", name="show_skatepark")
     */
    public function showSkateparks($id)
    {
        $repo = $this->getDoctrine()->getRepository(SkateParks::class);
        $skatepark = $repo->find($id);

        return $this->render('frontend/showSkatepark.html.twig', [
            'skatepark' => $skatepark
        ]);
    }

    /**
     * @Route("/SkateParksRegions", name="SkateParksRegions")
     */
    public function SkateParksRegions()
    {
        return $this->render('frontend/SkateParkMap.html.twig');
    }
}
