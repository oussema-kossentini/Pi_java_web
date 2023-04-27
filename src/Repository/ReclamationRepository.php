<?php

namespace App\Repository;

use App\Entity\Reclamation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\User;
use DateTime;
/**
 * @extends ServiceEntityRepository<Reclamation>
 *
 * @method Reclamation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reclamation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reclamation[]    findAll()
 * @method Reclamation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReclamationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reclamation::class);
    }

    public function save(Reclamation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Reclamation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    // public function getComplaintCounts()
    // {
    //     $entityManager = $this->getDoctrine()->getManager();
    
    //     $query = $entityManager->createQuery(
    //         'SELECT r.type, COUNT(r.id) as count
    //          FROM reclamation r
    //          GROUP BY r.type'
    //     );
    
    //     $results = $query->getResult();
    
    //     $complaintCounts = [];
    
    //     foreach ($results as $result) {
    //         $complaintCounts[$result['type']] = $result['count'];
    //     }
    
    //     return $complaintCounts;
    // }

    public function getComplaintCounts()
{
    $entityManager = $this->getDoctrine()->getManager();

    $query = $entityManager->createQuery(
        'SELECT r.type, COUNT(r.id) as count
         FROM App\Entity\Reclamation r
         GROUP BY r.type'
    );

    $results = $query->getResult();

    $complaintCounts = [];

    foreach ($results as $result) {
        $complaintCounts[$result['type']] = $result['count'];
    }

    return json_encode($complaintCounts);
}
    
    public function findRecbyname($name)
    {
        return $this->createQueryBuilder('reclamation')
            ->where('reclamation.type LIKE :sujet OR reclamation.description LIKE :sujet OR reclamation.state LIKE :sujet OR reclamation.email LIKE :sujet')
            ->setParameter('sujet','%'.$name.'%')
            ->getQuery()
            ->getResult();
    }

    public function trie_decroissant_type()
    {
        return $this->createQueryBuilder('reclamation')
            ->orderBy('reclamation.type','DESC')
            ->getQuery()
            ->getResult();
    }
    public function trie_decroissant_date()
    {
        return $this->createQueryBuilder('reclamation')
            ->orderBy('reclamation.date','DESC')
            ->getQuery()
            ->getResult();
    }

    function  getproduits()

    {

        $conn = $this->getEntityManager()

            ->getConnection();

        $sql='SELECT COUNT(reclamation.type) as num FROM reclamation GROUP BY (reclamation.type)' ;

  

        $stmt = $conn->prepare($sql);

        return $stmt->executeQuery()->fetchAllAssociative();




    }





//    /**
//     * @return Reclamation[] Returns an array of Reclamation objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Reclamation
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

// public function countByUserAndDate(User $user, DateTime $date): int
//     {
//         $qb = $this->createQueryBuilder('r');

//         return $qb
//             ->select($qb->expr()->count('r.id'))
//             ->where('r.user = :user')
//             ->andWhere('DATE(r.date) = :date')
//             ->setParameter('user', $user)
//             ->setParameter('date', $date->format('Y-m-d'))
//             ->getQuery()
//             ->getSingleScalarResult();
//     }


// }
}