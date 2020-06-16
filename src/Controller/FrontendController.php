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
    public function showhome()
    {
        $repo = $this->getDoctrine()->getRepository(SkateParks::class);
        $skateparks = $repo->findBy(array(), array('creation_date' => 'desc'), 3, 0);

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
     * @Route("/SkateParksMap", name="SkateParksMap")
     */
    public function showSkateParksMap()
    {
        return $this->render('frontend/SkateParkMap.html.twig');
    }

    /**
     * @Route("/SkateParksRegion/{region}", name="SkateParksRegion")
     */
    public function showSkateParksRegion($region)
    {
        $repo = $this->getDoctrine()->getRepository(SkateParks::class);
        $skatepark = $repo->findBy(array('region' => $region), array('creation_date' => 'desc'));

        return $this->render('frontend/SkateParksRegion.html.twig', [
            'skatepark' => $skatepark
        ]);
    }
}
