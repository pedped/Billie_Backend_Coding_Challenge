<?php

namespace App\Repository;

use App\Entity\InvoiceLineItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<InvoiceLineItem>
 *
 * @method InvoiceLineItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method InvoiceLineItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method InvoiceLineItem[]    findAll()
 * @method InvoiceLineItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InvoiceLineItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InvoiceLineItem::class);
    }

    public function add(InvoiceLineItem $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(InvoiceLineItem $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
