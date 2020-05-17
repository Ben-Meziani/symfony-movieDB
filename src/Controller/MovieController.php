<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Movie;
use App\Entity\Person;
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
    public function list(Request $request) {

        $search = $request->query->get("search", "");

        /* 
        $movies = $this->getDoctrine()->getRepository(Movie::class)->findBy(
            ["title" => $search], // WHERE title = "search"
            ["title" => "asc"]
        );

        // on a plutot besoin d'un title LIKE "%search%"
        */

        $movies = $this->getDoctrine()->getRepository(Movie::class)->findByPartialTitle($search);

        return $this->render('movie/list.html.twig', [
            "movies" => $movies,
            "search" => $search
        ]);
    }

    /**
     * @Route("/{id}/view", name="movie_view", requirements={"id" = "\d+"}, methods={"GET"})
     */
    public function view($id)
    {
        $movie = $this->getDoctrine()->getRepository(Movie::class)->findWithFullData($id);
        
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

            $categoryId = intval($request->request->get('categoryId'));
            if(empty($categoryId)) {
                $this->addFlash('warning', "La catégorie n'est pas valide !");
            }

            // je recupère la categorie correspondant a cet id
            $category = $this->getDoctrine()->getRepository(Category::class)->find($categoryId);
            if(empty($category)) {
                $this->addFlash('warning', "La catégorie séléctionnée n'existe pas !");
            }

            $personId = intval($request->request->get('personId'));
            $person = $this->getDoctrine()->getRepository(Person::class)->find($personId);
            if(empty($person)) {
                $this->addFlash('warning', "La personne séléctionnée n'existe pas !");
            }

            // Si les données sont bonnes 
            if(!empty($title) && !empty($releaseDate) && !empty($category) && !empty($person)) {
                // je recup le manager qui va persister mon entité
                $manager = $this->getDoctrine()->getManager();
                // Alors on crée une nouvelle entité qui contient ces données
                $movie = new Movie();
                $movie->setTitle($title);
                $movie->setReleaseDate(new \DateTime($releaseDate));
                // je lie l'objet category a l'objet movie, doctrine s'occupe de mettre dans la BDD les ID correspondant a cette ralation dans les colonne de clé étrangere
                $movie->addCategory($category);
                $movie->setDirector($person);
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
        
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
        $persons = $this->getDoctrine()->getRepository(Person::class)->findAll();

        return $this->render('movie/add.html.twig', [
            "categories" => $categories,
            "persons" => $persons
        ]);
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
            $releaseDate = $request->request->get('releaseDate');
            if(empty($releaseDate)) {
                $this->addFlash('warning', 'La date de sortie ne peut pas être vide !');
            }

            $categoryId = intval($request->request->get('categoryId'));
            if(empty($categoryId)) {
                $this->addFlash('warning', "La catégorie n'est pas valide !");
            }

            // je recupère la categorie correspondant a cet id
            $category = $this->getDoctrine()->getRepository(Category::class)->find($categoryId);
            if(empty($category)) {
                $this->addFlash('warning', "La catégorie séléctionnée n'existe pas !");
            }

            $personId = intval($request->request->get('personId'));
            $person = $this->getDoctrine()->getRepository(Person::class)->find($personId);
            if(empty($person)) {
                $this->addFlash('warning', "La personne séléctionnée n'existe pas !");
            }

            if(!empty($title) && !empty($releaseDate) && !empty($category) && !empty($person)) {
                // je recup le manager qui va persister mon entité
                $manager = $this->getDoctrine()->getManager();
                // Alors on crée une nouvelle entité qui contient ces données
                $movie->setTitle($title);
                $movie->setReleaseDate(new \DateTime($releaseDate));
                // je lie l'objet category a l'objet movie, doctrine s'occupe de mettre dans la BDD les ID correspondant a cette ralation dans les colonne de clé étrangere
                $movie->addCategory($category);
                $movie->setDirector($person);
                // je demande au manager de pousser dans la BDD toute les modifications ou ajout d'entités
                $manager->flush();

                // je demande au navigateur d'aller sur la liste de films
                // permet d'aviter les double soumission de formulaire et le "back" du navigateur
                return $this->redirectToRoute('movie_list');
            }

        }

        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
        $persons = $this->getDoctrine()->getRepository(Person::class)->findAll();

        return $this->render('movie/update.html.twig', [
            'movie' => $movie,
            "categories" => $categories,
            "persons" => $persons
        ]);
    }

}
