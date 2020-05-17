<?php

namespace App\Controller;

use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/category")
 */
class CategoryController extends AbstractController
{
    /**
     * Ici on demande en parametre de notre methode de controller un objet de type Category
     * Catregory etant une entité, Doctrine va essayer d'utiliser les parametres de la route pour retrouver l'entité Category correspondant a l'id passé dans la route
     * 
     * @Route("/{id}/view", name="category_view", requirements={"id" = "\d+"}, methods={"GET"})
     */
    public function viewCategory(Category $category)
    {
        return $this->render('category/view.html.twig', [
            'category' => $category,
        ]);
    }

    /**
     * @Route("/list", name="category_list", methods={"GET"})
     */
    public function listCategories()
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
        return $this->render('category/list.html.twig', [
            'categories' => $categories,
        ]);
    }

}
