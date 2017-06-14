<?php

namespace TrailWarehouse\AppBundle\Repository;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;

/**
 * MemberRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MemberRepository extends CommonRepository implements UserLoaderInterface
{
  public function loadUserByUsername($username)
   {
      return $this->createQueryBuilder('u')
        ->where('u.username = :username OR u.email = :email')
        ->setParameter('username', $username)
        ->setParameter('email', $username)
        ->getQuery()
        ->getOneOrNullResult()
      ;
  }
}
