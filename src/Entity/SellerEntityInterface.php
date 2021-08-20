<?php

namespace App\Entity;

use Symfony\Component\Security\Core\User\UserInterface;

interface SellerEntityInterface
{
    public function setSeller(UserInterface $user): SellerEntityInterface;
}
