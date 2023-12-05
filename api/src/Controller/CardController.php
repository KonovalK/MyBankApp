<?php

namespace App\Controller;

use ApiPlatform\Validator\ValidatorInterface;
use App\Entity\Card;
use App\Entity\SavingsBank;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class CardController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var ValidatorInterface
     */
    private ValidatorInterface $validator;
    /**
     * @var DenormalizerInterface
     */
    private DenormalizerInterface $denormalizer;

    /**
     * @param EntityManagerInterface $entityManager
     * @param ValidatorInterface $validator
     * @param DenormalizerInterface $denormalizer
     */
    public function __construct(EntityManagerInterface $entityManager, ValidatorInterface $validator, DenormalizerInterface $denormalizer)
    {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
        $this->denormalizer = $denormalizer;
    }

    /**
     * @return JsonResponse
     */
    #[Route('/delete-card/{selectedCard}', name: 'delete_card', methods: "DELETE")]
    public function deleteCard(int $selectedCard): JsonResponse
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->json([
                'message' => 'missing credentials',
            ], Response::HTTP_UNAUTHORIZED);
        }
        if (!in_array(User::ROLE_ADMIN, $user->getRoles())) {
            return new JsonResponse([], Response::HTTP_FORBIDDEN);
        }

        $currentCardRepository = $this->entityManager->getRepository(Card::class);
        /** @var Card|null $currentCard */
        $currentCard = $currentCardRepository->findOneBy(['id' => $selectedCard]);


        $this->entityManager->remove($currentCard);
        $this->entityManager->flush();

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }
}
