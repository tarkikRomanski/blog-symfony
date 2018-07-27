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
            ->andWhere('user_agent', 'LIKE', '%Chrome/%')
            ->andWhere('user_agent', 'NOT LIKE', '%Chromium/%')
            ->getQuery()
            ->getArrayResult();
    }

    public function getSeamonkeyUsers()
    {
        return $this->createQueryBuilder('s')
            ->andWhere('user_agent', 'LIKE', '%Seamonkey/%')
            ->getQuery()
            ->getArrayResult();
    }

    public function getFirefoxUsers()
    {
        return $this->createQueryBuilder('s')
            ->andWhere('user_agent', 'LIKE', '%Firefox/%')
            ->andWhere('user_agent', 'NOT LIKE', '%Seamonkey/%')
            ->getQuery()
            ->getArrayResult();
    }

    public function getChromiumUsers()
    {
        return $this->createQueryBuilder('s')
            ->andWhere('user_agent', 'LIKE', '%Chromium/%')
            ->getQuery()
            ->getArrayResult();
    }

    public function getOperaUsers()
    {
        return $this->createQueryBuilder('s')
            ->andWhere('user_agent', 'LIKE', '%Opera/%')
            ->orWhere('user_agent', 'LIKE', '%OPR/%')
            ->getQuery()
            ->getArrayResult();
    }

    public function getSafariUsers()
    {
        return $this->createQueryBuilder('s')
            ->andWhere('user_agent', 'LIKE', '%Chrome/%')
            ->andWhere('user_agent', 'NOT LIKE', '%Chromium/%')
            ->getQuery()
            ->getArrayResult();
    }

    public function getIeUsers()
    {
        return $this->createQueryBuilder('s')
            ->andWhere('user_agent', 'LIKE', '%; MSIE %')
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
