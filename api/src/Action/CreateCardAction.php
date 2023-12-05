<?php

namespace App\Action;

use ApiPlatform\Validator\ValidatorInterface;
use App\Entity\Card;
use App\Entity\User;
use App\Services\CreateRandomCardService;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Security;

class CreateCardAction
{

    /**
     * @param Security $security
     * @param ValidatorInterface $validator
     * @param CreateRandomCardService $cardService
     */
    public function __construct(
        private Security                    $security,
        private ValidatorInterface          $validator,
        private CreateRandomCardService     $cardService,
    ){}

    /**
     * @param Card $data
     * @return Card
     */
    public function __invoke(Card $data): Card
    {
        /** @var User $currentUser */
        $currentUser = $this->security->getUser();

        $this->validator->validate($data);

        //create card info


        $data->setUser($currentUser);

        $cardNum=$this->cardService->GenerateRandomCardNum('');
        $data->setCardNumber($cardNum);

        $cvv = rand(100, 999);
        $data->setCvv($cvv);

        $data->setExpirationDate($this->cardService->generateExpirationDate());

        return $data;
    }

}