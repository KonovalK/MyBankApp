<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class LoginController extends AbstractController
{

    /**
     * @param Security $security
     */
    public function __construct(private Security $security){}

    /**
     * @return JsonResponse
     */
    #[Route('/login', name: 'app_login')]
    public function index(): JsonResponse
    {
        $user = $this->security->getUser();

        if (!$user) {
            return $this->json([
                'message' => 'missing credentials',
            ], Response::HTTP_UNAUTHORIZED);
        }

        return $this->json([
            'user' => $user->getUserIdentifier()
        ]);
    }

}
