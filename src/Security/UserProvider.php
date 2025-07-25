<?php

namespace App\Security;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\ApiUser;

class UserProvider implements UserProviderInterface
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function loadUserByIdentifier(string $apiKey): UserInterface
    {
        $user = $this->em->getRepository(ApiUser::class)->findOneBy(['apikey' => $apiKey]);

        if (!$user) {
            throw new UserNotFoundException('API Key no válida');
        }

        return $user;
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        throw new \Exception('No soportado');
    }

    public function supportsClass(string $class): bool
    {
        return ApiUser::class === $class;
    }
}
