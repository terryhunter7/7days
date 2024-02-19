<?php

namespace Domain\User;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class UserRegistration
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function registerValidUser(User $user): void
    {
        $this->em->persist($user);
        $this->em->flush();
    }
}
