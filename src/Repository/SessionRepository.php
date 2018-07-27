<?php

namespace App\Repository;

use App\Entity\Session;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Session|null find($id, $lockMode = null, $lockVersion = null)
 * @method Session|null findOneBy(array $criteria, array $orderBy = null)
 * @method Session[]    findAll()
 * @method Session[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SessionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Session::class);
    }

    public function getChromeUsers()
    {
        return $this->createQueryBuilder('s')
            ->where('s.user_agent LIKE :detect')
            ->andWhere('s.user_agent NOT LIKE :not')
            ->setParameter('detect', '%Chrome/%')
            ->setParameter('not', '%Chromium/%')
            ->getQuery()
            ->getArrayResult();
    }

    public function getSeamonkeyUsers()
    {
        return $this->createQueryBuilder('s')
            ->where('s.user_agent LIKE :detect')
            ->setParameter('detect', '%Seamonkey/%')
            ->getQuery()
            ->getArrayResult();
    }

    public function getFirefoxUsers()
    {
        return $this->createQueryBuilder('s')
            ->where('s.user_agent LIKE :detect')
            ->andWhere('s.user_agent NOT LIKE :not')
            ->setParameter('detect', '%Firefox/%')
            ->setParameter('not', '%Seamonkey/%')
            ->getQuery()
            ->getArrayResult();
    }

    public function getChromiumUsers()
    {
        return $this->createQueryBuilder('s')
            ->where('s.user_agent LIKE :detect')
            ->setParameter('detect', '%Chromium/%')
            ->getQuery()
            ->getArrayResult();
    }

    public function getOperaUsers()
    {
        return $this->createQueryBuilder('s')
            ->where('s.user_agent LIKE :detect1')
            ->orWhere('s.user_agent LIKE :detect2')
            ->setParameter('detect1', '%Opera/%')
            ->setParameter('detect2', '%Chromium/%')
            ->getQuery()
            ->getArrayResult();
    }

    public function getSafariUsers()
    {
        return $this->createQueryBuilder('s')
            ->where('s.user_agent LIKE :detect')
            ->andWhere('s.user_agent NOT LIKE :not1')
            ->andWhere('s.user_agent NOT LIKE :not2')
            ->setParameter('detect', '%Safari/%')
            ->setParameter('not1', '%Chrome/%')
            ->setParameter('not2', '%Chromium/%')
            ->getQuery()
            ->getArrayResult();
    }

    public function getIeUsers()
    {
        return $this->createQueryBuilder('s')
            ->where('s.user_agent LIKE :detect')
            ->setParameter('detect', '%; MSIE %')
            ->getQuery()
            ->getArrayResult();
    }

//    /**
//     * @return Session[] Returns an array of Session objects
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
    public function findOneBySomeField($value): ?Session
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
