<?php

namespace App\Repository;

use App\Entity\Facture;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Factures>
 *
 * @method Factures|null find($id, $lockMode = null, $lockVersion = null)
 * @method Factures|null findOneBy(array $criteria, array $orderBy = null)
 * @method Factures[]    findAll()
 * @method Factures[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FactureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Facture::class);
    }

    public function save(Facture $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Facture $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    function  getproduits()

    {

        $conn = $this->getEntityManager()

            ->getConnection();

        $sql='SELECT COUNT(facture.ville) as num FROM facture GROUP BY (facture.ville)' ;

  

        $stmt = $conn->prepare($sql);

        return $stmt->executeQuery()->fetchAllAssociative();




    }
    function searchQB($searchTerm)
    {
        return $this->createQueryBuilder('r')
            ->where('r.nom LIKE :searchTerm')
            ->orWhere('r.prenom LIKE :searchTerm')
            ->orWhere('r.ville LIKE :searchTerm')
            ->setParameter('searchTerm', '%'.$searchTerm.'%')
            ->getQuery()
            ->getResult();
    }
}