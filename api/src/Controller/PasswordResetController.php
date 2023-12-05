<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class PasswordResetController extends AbstractController
{

    /**
     * @param EntityManagerInterface $entityManager
     * @param UserPasswordHasherInterface $passwordHasher
     */
    public function __construct(private EntityManagerInterface $entityManager, private UserPasswordHasherInterface $passwordHasher){}

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    #[Route('/confirm-email', name: 'confirm_email', methods: ["POST"])]
    public function confirmEmail(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $email = $data["email"];

        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

        if (!$user) {
            return $this->json(['message' => 'No user with this email'], Response::HTTP_NOT_FOUND);
        }

        return $this->json(['message' => 'Email correct'], Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('/reset-password', name: 'reset_password', methods: ["PUT"])]
    public function ResetPassword(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $email = $data["email"];
        $password = $data['password'];

        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

        $hashedPassword = $this->passwordHasher->hashPassword($user, $password);
        $user->setPassword($hashedPassword);

        $this->entityManager->flush();

        return $this->json(['message' => 'Password reset successfully'], Response::HTTP_OK);
    }

}