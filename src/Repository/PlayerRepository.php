<?php

namespace App\Repository;

use App\Entity\Player;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Player>
 *
 * @method Player|null find($id, $lockMode = null, $lockVersion = null)
 * @method Player|null findOneBy(array $criteria, array $orderBy = null)
 * @method Player[]    findAll()
 * @method Player[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlayerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Player::class);
    }

    public function add(Player $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Player $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findPlayersNotInTeam($teamId)
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.team', 't')
            ->andWhere('t.id != :teamId OR t.id IS NULL')
            ->setParameter('teamId', $teamId)
            ->getQuery()
            ->getResult();
    }

    public function findPlayersInTeam($teamId)
    {
        return $this->createQueryBuilder('p')
            ->join('p.team', 't')
            ->andWhere('t.id = :teamId')
            ->setParameter('teamId', $teamId)
            ->getQuery()
            ->getResult();
        }
}
