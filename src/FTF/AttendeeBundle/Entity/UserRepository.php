<?php

namespace FTF\AttendeeBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends EntityRepository
{
    public function clear()
    {
        $query = $this->_em->createQuery('DELETE FTF\AttendeeBundle\Entity\User');
        $query->execute();
    }
}