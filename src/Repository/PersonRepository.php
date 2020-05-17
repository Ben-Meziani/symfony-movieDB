<?php

namespace App\Repository;

use App\Entity\Person;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class PersonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Person::class);
    }

    public function findWithFullData($id) {
        $builder = $this->createQueryBuilder('p');
        $builder->where("p.id = :id");
        $builder->setParameter('id', $id);

        $builder->leftJoin('p.writedMovies', 'wm');
        $builder->addSelect('wm');

        $builder->leftJoin('p.directedMovies', 'dm');
        $builder->addSelect('dm');

        $builder->leftJoin('p.movieActors', 'ma');
        $builder->addSelect('ma');

        $query = $builder->getQuery();
        return $query->getOneOrNullResult();
    }
}
