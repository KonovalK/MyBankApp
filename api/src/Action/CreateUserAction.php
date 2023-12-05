<?php

namespace App\Action;

use ApiPlatform\Validator\ValidatorInterface;
use App\Entity\User;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Security;

class CreateUserAction
{

    /**
     * @param Security $security
     * @param UserPasswordHasherInterface $passwordHasher
     * @param ValidatorInterface $validator
     */
    public function __construct(
        private Security                    $security,
        private UserPasswordHasherInterface $passwordHasher,
        private ValidatorInterface          $validator,
    ){}

    /**
     * @param User $data
     * @return User
     */
    public function __invoke(User $data): User
    {
        /** @var User $currentUser */
        $currentUser = $this->security->getUser();

        $this->validator->validate($data);

        //hash password
        $hashedPassword = $this->passwordHasher->hashPassword($data, $data->getPassword());
        $data->setPassword($hashedPassword);

        return $data;
    }

}