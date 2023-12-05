<?php

namespace App\Controller;

use ApiPlatform\Validator\ValidatorInterface;
use App\Entity\Card;
use App\Entity\User;
use App\Entity\Verification;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class RegistrationController extends AbstractController
{

    /**
     * @param UserPasswordHasherInterface $passwordHasher
     * @param EntityManagerInterface $entityManager
     * @param ValidatorInterface $validator
     * @param DenormalizerInterface $denormalizer
     */
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
        private EntityManagerInterface      $entityManager,
        private ValidatorInterface          $validator,
        private DenormalizerInterface       $denormalizer
    ){}

    /**
     * @throws ExceptionInterface
     */
    #[Route('/register', name: 'app_register', methods: ['POST'])]
    public function registration(Request $request): JsonResponse
    {
        $user = $this->createUser($request);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return new JsonResponse([], Response::HTTP_CREATED);
    }

    /**
     * @param Request $request
     * @return User
     * @throws ExceptionInterface
     * @throws Exception
     */
    private function createUser(Request $request): User
    {
        $content = $request->getContent();

        if (!$content) {
            throw new UnprocessableEntityHttpException("Data required");
        }

        $requestData = json_decode($request->getContent(), true);

        /** @var User $user */
        $user = $this->denormalizer->denormalize($requestData, User::class, "array");

        $this->validator->validate($user);

        $hashedPassword = $this->passwordHasher->hashPassword($user, $user->getPassword());
        $user->setPassword($hashedPassword);

        $user->setRoles([User::ROLE_GUEST]);

        return $user;
    }

}