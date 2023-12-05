<?php

namespace App\Controller;

use ApiPlatform\Validator\ValidatorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class UpdateUserController extends AbstractController
{

    /**
     * @param Security $security
     * @param EntityManagerInterface $entityManager
     * @param ValidatorInterface $validator
     */
    public function __construct(
        private Security               $security,
        private EntityManagerInterface $entityManager,
        private ValidatorInterface     $validator
    )
    {
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    #[Route("update-user", methods: "PUT")]
    public function updateUserByEmail(Request $request): JsonResponse
    {
        $user = $this->security->getUser();
        $data = json_decode($request->getContent(), true);

        if (!$user) {
            return $this->json(['message' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        if (!isset(
            //$data["email"],
            $data['surname'],
            $data['name'],
            $data['phoneNumber']
        )) {
            return $this->json(['message' => 'Wrong type'], Response::HTTP_BAD_REQUEST);
        }

        //$user->setEmail($data["email"]);
        $user->setSurname($data['surname']);
        $user->setName($data['name']);
        $user->setPhoneNumber($data['phoneNumber']);

        $this->validator->validate($user);

        $this->entityManager->flush();

        return $this->json(['message' => 'Profile was updated'], Response::HTTP_OK);
    }

}
