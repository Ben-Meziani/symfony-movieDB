<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
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

    /**
     * @Route("/add", name="category_add", methods={"GET", "POST"})
     */
    public function addCategory(Request $request)
    {
        // creer une entité qui sera gérée par le formulaire
        $newCategory = new Category();

        // Crée le formulaire vide
        // je donnée au builder l'objet qui devra etre géré par le formulaire
        /*$builder = $this->createFormBuilder($newCategory);
        $builder->add("label", TextType::class, ["label" => "Libellé de la catégorie"]);
        $builder->add("submit", SubmitType::class, ["label" => "Valider"]);
        $form = $builder->getForm();*/

        // je crée un fomulaire grace a ma classe CategoryType
        // Symfony va automatiquement appeler la methode buildForm() de cette classe
        $form = $this->createForm(CategoryType::class, $newCategory);

        // je demande au formulaire de traiter la request
        // on va recupérer les données du GET/POST
        // on  va remplir l'objet sous-jacent
        $form->handleRequest($request);
        // A ce moment le formualire sait si des données ont été postées
        if($form->isSubmitted() && $form->isValid()) {
            // si des données ont été soumises , on traite le formulaire
            //$data = $form->getData();
            // pas besoin de ce getData car l'objet  géré par le formulaire c'est $newCategory
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($newCategory);
            $manager->flush();

            return $this->redirectToRoute('category_list');
        }

        // on envoi le formulaire a la template
        return $this->render(
            'category/add.html.twig',
            [
                "form" => $form->createView()
            ]
        );
    }

}
