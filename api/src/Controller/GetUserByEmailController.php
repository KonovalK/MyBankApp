<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class GetUserByEmailController extends AbstractController
{

    /**
     *
     */
    public function __construct(){}

    /**
     * @return JsonResponse
     */
    #[Route('/username', name: 'get_username', methods: "GET")]
    public function index(): JsonResponse
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->json([
                'message' => 'missing credentials',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $name = $user->getName();
        $surname = $user->getSurname();
        $phoneNumber = $user->getPhoneNumber();
        $email = $user->getEmail();

        return $this->json([
            'name'        => $name,
            'surname'     => $surname,
            'phoneNumber' => $phoneNumber,
            'email'       => $email
        ]);
    }

}
