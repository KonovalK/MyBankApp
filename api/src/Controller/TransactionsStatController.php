<?php

namespace App\Controller;

use App\Entity\Card;
use App\Entity\Transaction;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TransactionsStatController extends AbstractController
{

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    /**
     * @return JsonResponse
     */
    #[Route('/get-transactions-stat', name: 'get_transactions_stat', methods: ['GET'])]
    public function getWebsiteStat(Request $request): JsonResponse
    {
        $user = $this->getUser();

        if (!in_array(User::ROLE_USER, $user->getRoles())) {
            return new JsonResponse([], Response::HTTP_NOT_FOUND);
        }

        $cardNumber = $request->query->get('card');
        $currentCardRepository = $this->entityManager->getRepository(Card::class);
        /** @var Card|null $currentCard */
        $currentCard = $currentCardRepository->findOneBy(['cardNumber' => $cardNumber]);

        $currentUserRepository = $this->entityManager->getRepository(User::class);
        /** @var User|null $currentUser */
        $currentUser = $currentUserRepository->findOneBy(['email' => $user->getUserIdentifier()]);

        if (!$currentUser->getCards()->contains($currentCard)) {
            return new JsonResponse("Forbidden", Response::HTTP_FORBIDDEN);
        }


        $stat = $this->entityManager->getRepository(Transaction::class)->findStatOfDate($cardNumber);

        if (isset($stat[0]['date'])) {
            foreach ($stat as &$result) {
                $formattedDate = (new \DateTime('1970-01-01'))->modify('+' . $result['date'] . ' days')->format('Y-m-d');
                $result['date'] = $formattedDate;
            }
        }

        return new JsonResponse($stat);
    }

}