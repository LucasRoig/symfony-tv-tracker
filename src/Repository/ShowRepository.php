<?php

namespace App\Repository;

use App\Entity\Show;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Show|null find($id, $lockMode = null, $lockVersion = null)
 * @method Show|null findOneBy(array $criteria, array $orderBy = null)
 * @method Show[]    findAll()
 * @method Show[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShowRepository extends ServiceEntityRepository
{
    private $tmdbRepository;
    public function __construct(RegistryInterface $registry, TmdbRepository $tmdbRepository)
    {
        parent::__construct($registry, Show::class);
        $this->tmdbRepository = $tmdbRepository;
    }

    public function findOneByTmdbId($tmdbId){
        $show = $this->findOneBy(['tmdb_id'=>$tmdbId]);
        if (isset($show) && false) return $show;
        else return $this->fetchShowFromApi($tmdbId,$show);
    }

    /**
     * @param $tmdbId
     * @param null $oldshow
     * @return mixed
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * Fetches a show from tmdb if an oldshow is passed the oldshow will be updated with the new informations fetched
     */
    private function fetchShowFromApi($tmdbId,$oldshow = null){
        $show = $this->tmdbRepository->getShow($tmdbId);
        $newShow = isset($oldshow)? $oldshow : new Show();
        $newShow->setBackdropPath($show['backdrop_path'])
            ->setName($show['name'])
            ->setFirstAirDate(new \DateTime($show['first_air_date']))
            ->setStatus($show['status'])
            ->setOverview($show['overview'])
            ->setPosterPath($show['poster_path'])
            ->setTmdbId($show['id']);
        $this->getEntityManager()->persist($newShow);
        $this->getEntityManager()->flush();
        return $newShow;
    }
//    /**
//     * @return Show[] Returns an array of Show objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Show
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
