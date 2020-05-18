<?php

namespace App\Repository;

use App\Entity\Movie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class MovieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Movie::class);
    }

    // doit renvoyer une liste de films dont le tittre contient $partialTitle
    public function findByPartialTitle($partialTitle)
    {

        $builder = $this->createQueryBuilder('movie');

        // Methode DQL Doctrine Query Language
        $builder->where("movie.title LIKE :partialTitle");
        $builder->setParameter(':partialTitle', "%$partialTitle%");

        // les galaxy
        // nous donne deux conditions title LIKE "%les%" ET title LIKE "%galay%"
        /*
        $titleParts = explode(' ', $partialTitle);
        foreach ($titleParts as $part) {
            $builder->andWhere();
        }
        */

        $builder->orderBy('movie.title', 'ASC');
        $query = $builder->getQuery();
        return $query->execute();
    }

    // cette methode de repository custom me permet de ;récupérer un film et les objets character lié
    public function findWithFullData($id)
    {
        // je crée un querybuilder sur l'objet Movie avec l'alias 'movie'
        $builder = $this->createQueryBuilder('movie');
        // je met ma condition de recherche
        $builder->where("movie.id = :id");
        // J'ajoute la valeur du parametre utilisé dans ma condition
        $builder->setParameter('id', $id);

        // je crée une jointure avec la table movieactor
        $builder->leftJoin('movie.movieActors', 'actor');
        // J'ajoute l'acteur au select pour que doctrine alimente les objets associés
        $builder->addSelect('actor');
        // je crée la jointure avec les personnes
        $builder->leftJoin('actor.person', 'person');
        // J'ajoute la personne au select pour que doctrine alimente les objets associés
        $builder->addSelect('person');

        // Pareil pour les articles
        $builder->leftJoin('movie.posts', 'post');
        $builder->addSelect('post');

        $builder->leftJoin('movie.director', 'director');
        $builder->addSelect('director');

        $builder->leftJoin('movie.writers', 'writer');
        $builder->addSelect('writer');

        $builder->leftJoin('movie.categories', 'category');
        $builder->addSelect('category');

        $builder->leftJoin('movie.awards', 'award');
        $builder->addSelect('award');

        // j'execute la requete
        $query = $builder->getQuery();
        // je recupére le resultat non pas sous la forme d'un tableau mais un ou 0 objets
        $result = $query->getOneOrNullResult();

        return $result;
    }
}
