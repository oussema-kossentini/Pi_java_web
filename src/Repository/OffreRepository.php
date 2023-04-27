<?php

namespace App\Repository;

use App\Entity\Offre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Offre>
 *
 * @method Offre|null find($id, $lockMode = null, $lockVersion = null)
 * @method Offre|null findOneBy(array $criteria, array $orderBy = null)
 * @method Offre[]    findAll()
 * @method Offre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OffreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Offre::class);
        
    }
    public function findById($idOffre): ?Offre
    {
        return $this->createQueryBuilder('o')
            ->where('o.idOffre = :value')
            ->setParameter('value', $idOffre)
            ->getQuery()
            ->getOneOrNullResult();
    }
    function searchQB($searchTerm)
    {
        return $this->createQueryBuilder('off')
        ->leftJoin('off.sponsor', 'sp')
            ->where('off.remise LIKE :searchTerm')
            ->orWhere('off.dateDebut LIKE :searchTerm')
            ->orWhere('off.dateFin LIKE :searchTerm')
            ->orWhere('sp.nomSponsor LIKE :searchTerm')
            ->setParameter('searchTerm', '%'.$searchTerm.'%')
            ->getQuery()
            ->getResult();
    }
    // OffreRepository.php

public function findSimilaires(Offre $offre): array
{
    $qb = $this->createQueryBuilder('o');

    return $qb->andWhere('o.remise = :remise')
              ->andWhere('o.idOffre != :idOffre')
              ->setParameter('remise', $offre->getRemise())
              ->setParameter('idOffre', $offre->getIdOffre())
              ->orderBy('o.dateDebut', 'DESC')
              ->setMaxResults(5)
              ->getQuery()
              ->getResult();
}

    function orderByDateQB()
    {
        $req=$this->createQueryBuilder('off')->orderBy('off.dateDebut','DESC');
        return $req->getQuery()->getResult();
    }
    function orderByDateQB1()
    {
        $req=$this->createQueryBuilder('off')->orderBy('off.dateFin','DESC');
        return $req->getQuery()->getResult();
    }
    function orderByDateQB2()
    {
        $req=$this->createQueryBuilder('off')->orderBy('off.remise','DESC');
        return $req->getQuery()->getResult();
    }

/*
    public function save(Offre $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Offre $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }*/

//    /**
//     * @return Offre[] Returns an array of Offre objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('o.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Offre
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
