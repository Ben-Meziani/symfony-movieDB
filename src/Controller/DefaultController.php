<?php

namespace App\Controller;

use App\Entity\Movie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function homepage()
    {
        // je souhaite utiliser doctrine pour recupérer des entité (qui contienent les données provenant de ma base de donnée)
        // $this->getDoctrine() me renvoi le SERVICE doctrine qui me permet d'interagir avec doctrine
        // ->getRepository(Movie::class) je demande le repository correspondant aux entités de type Movie (decrite dans la class Movie)
        // ->findAll() methode existante par defaut dans les repository, permet de 
        $movies = $this->getDoctrine()->getRepository(Movie::class)->findAll();
        return $this->render('homepage.html.twig', [
            "movies" => $movies
        ]);
    }
}
