<?php

namespace App\Controller;

use App\Entity\Movie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/movie")
 */
class MovieController extends AbstractController
{

    /**
     * @Route("/list", name="movie_list", methods={"GET"})
     */
    public function list() {
        $movies = $this->getDoctrine()->getRepository(Movie::class)->findAll();
        
        return $this->render('movie/list.html.twig', [
            "movies" => $movies
        ]);
    }

    /**
     * @Route("/{id}/view", name="movie_view", requirements={"id" = "\d+"}, methods={"GET"})
     */
    public function view(Movie $movie)
    {
        // Pas besoin car on utilise le paramConverter de Doctrine
        // il s'occupe de recuperer mon entité grace aux parametres de la route
        // $movie = $this->getDoctrine()->getRepository(Movie::class)->find($id);

        if(!$movie) {
            throw $this->createNotFoundException("Ce film n'existe pas !");
        }

        return $this->render('movie/view.html.twig', [
            'movie' => $movie,
        ]);
    }

    /**
     * @Route("/add", name="movie_add", methods={"GET", "POST"})
     */
    public function add(Request $request) {

        if($request->getMethod() == Request::METHOD_POST) {

            $title = $request->request->get('title');
            if(empty($title)) {
                $this->addFlash('warning', 'Le titre ne peut pas être vide !');
            }

            $releaseDate = $request->request->get('releaseDate');
            if(empty($releaseDate)) {
                $this->addFlash('warning', 'La date de sortie ne peut pas être vide !');
            }

            // Si les données sont bonnes 
            if(!empty($title) && !empty($releaseDate)) {
                // je recup le manager qui va persister mon entité
                $manager = $this->getDoctrine()->getManager();
                // Alors on crée une nouvelle entité qui contient ces données
                $movie = new Movie();
                $movie->setTitle($title);
                $movie->setReleaseDate(new \DateTime($releaseDate));
                // Et l'ajouter en BDD
                // je dit au manager qu'il y a une nouvelle entité à gerer
                $manager->persist($movie);
                // je demande au manager de pousser dans la BDD toute les modifications ou ajout d'entités
                $manager->flush();

                // je demande au navigateur d'aller sur la liste de films
                // permet d'aviter les double soumission de formulaire et le "back" du navigateur
                return $this->redirectToRoute('movie_list');
            }

        }
        
        return $this->render('movie/add.html.twig');
    }

    
    /**
     * @Route("/{id}/delete", name="movie_delete", methods={"GET"})
     */
    public function delete($id) {
        // je recupère mon entité
        $movie = $this->getDoctrine()->getRepository(Movie::class)->find($id);
        
        // si le film n'éxiste pas on renvoi sur une 404
        if(!$movie) {
            throw $this->createNotFoundException("Ce film n'existe pas !");
        }

        // je demande le manager
        $manager = $this->getDoctrine()->getManager();
        // je dit au manager que cette entité devra faire l'objet d'une suppression
        $manager->remove($movie);
        // je demande au manager d'executer dans la BDD toute les modifications qui ont été faites sur les entités
        $manager->flush();
        // On retourne sur la liste des films
        return $this->redirectToRoute('movie_list');
    }

    
    /**
     * @Route("/{id}/update", name="movie_update", requirements={"id" = "\d+"}, methods={"GET", "POST"})
     */
    public function update(Movie $movie, Request $request)
    {
        if(!$movie) {
            throw $this->createNotFoundException("Ce film n'existe pas !");
        }

        
        if($request->getMethod() == Request::METHOD_POST) {
            
            $title = $request->request->get('title');
            if(empty($title)) {
                $this->addFlash('warning', 'Le titre ne peut pas être vide !');
            }

            if(!empty($title)) {
                // je recup le manager qui va persister mon entité
                $manager = $this->getDoctrine()->getManager();
                // Alors on crée une nouvelle entité qui contient ces données
                $movie->setTitle($title);
                // je demande au manager de pousser dans la BDD toute les modifications ou ajout d'entités
                $manager->flush();

                // je demande au navigateur d'aller sur la liste de films
                // permet d'aviter les double soumission de formulaire et le "back" du navigateur
                return $this->redirectToRoute('movie_list');
            }

        }

        return $this->render('movie/update.html.twig', [
            'movie' => $movie,
        ]);
    }

}
