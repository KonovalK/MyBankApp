<?php

namespace App\Controller;

use ApiPlatform\Validator\ValidatorInterface;
use App\Entity\Card;
use App\Entity\Transaction;
use App\Entity\User;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class TransactionController extends AbstractController
{
    private $entityManager;
    private ValidatorInterface $validator;
    private DenormalizerInterface $denormalizer;

    public function __construct(EntityManagerInterface $entityManager, ValidatorInterface $validator, DenormalizerInterface $denormalizer)
    {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
        $this->denormalizer = $denormalizer;
    }

    /**
     * @return JsonResponse
     */
    #[Route('/get-transactions', name: 'get_transactions', methods: "GET")]
    public function index(Request $request): JsonResponse
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->json([
                'message' => 'missing credentials',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $cardNumber = $request->query->get('senderCard');
        $itemsPerPage = $request->query->get('itemsPerPage');
        $page = $request->query->get('page');
        $receiver = $request->query->get('receiver');
        $description = $request->query->get('description');
        $sortField = $request->query->get('sort');
        $sortOrder = $request->query->get('direction');

        $currentCardRepository = $this->entityManager->getRepository(Card::class);
        /** @var Card|null $currentCard */
        $currentCard = $currentCardRepository->findOneBy(['cardNumber' => $cardNumber]);

        $currentUserRepository = $this->entityManager->getRepository(User::class);
        /** @var User|null $currentUser */
        $currentUser = $currentUserRepository->findOneBy(['email' => $user->getUserIdentifier()]);

        if (!$currentUser->getCards()->contains($currentCard)) {
            return new JsonResponse("Forbidden", Response::HTTP_FORBIDDEN);
        }

        $transactionRepository = $this->entityManager->getRepository(Transaction::class);
        $transactions = $transactionRepository->findTransactionsWithUsersByCard($cardNumber, $itemsPerPage, $page, $receiver, $description, $sortField, $sortOrder);
        $transactionsCount=$transactionRepository->findTransactionsCount($cardNumber, $receiver, $description);

        $response = [
            'hydra:member' => $transactions,
            'hydra:totalItems' => $transactionsCount,
        ];

        return new JsonResponse($response, Response::HTTP_OK);
    }

    /**
     * @return JsonResponse
     */
    #[Route('/post-transactions', name: 'post_transactions', methods: "POST")]
    public function postTransactions(Request $request): JsonResponse
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->json([
                'message' => 'missing credentials',
            ], Response::HTTP_UNAUTHORIZED);
        }
        $content = $request->getContent();

        if (!$content) {
            throw new UnprocessableEntityHttpException("Data required");
        }

        $requestData = json_decode($request->getContent(), true);

        $pinCode=intval($requestData['pinCode']);

        /** @var Transaction $transaction */
        $transaction = $this->denormalizer->denormalize($requestData, Transaction::class, "array");

        $this->validator->validate($transaction);

        $currentUserRepository = $this->entityManager->getRepository(User::class);
        /** @var User|null $currentUser */
        $currentUser = $currentUserRepository->findOneBy(['email' => $user->getUserIdentifier()]);

        $transaction->setSender($currentUser);

        /** @var User|null $receiver */
        $receiver = $currentUserRepository->findUserByCardNumber($transaction->getReceiverCard());

        if (!$receiver){
            return new JsonResponse("Отримувача з таким номером карти не існує!", Response::HTTP_BAD_REQUEST);
        }

        $currentCardRepository = $this->entityManager->getRepository(Card::class);
        /** @var Card|null $currentCard */
        $currentCard = $currentCardRepository->findOneBy(['cardNumber' => $transaction->getSenderCard()]);

        if($currentCard->getPin() != $pinCode){
            return new JsonResponse("Не вірний пін-код, спробуйте ще раз.", Response::HTTP_BAD_REQUEST);
        }

        $receiverCard = $currentCardRepository->findOneBy(['cardNumber' => $transaction->getReceiverCard()]);

        if ($transaction->getSumma() > $currentCard->getBalance()){
            return new JsonResponse("Сума переказу не може перевищувати балансу на карті!", Response::HTTP_BAD_REQUEST);
        }

        if (!$currentUser->getCards()->contains($currentCard)) {
            return new JsonResponse("Forbidden", Response::HTTP_FORBIDDEN);
        }
        if (!($currentCard->getRate()->getId() === $receiverCard->getRate()->getId())) {
            return new JsonResponse("Не можна переказувати гроші на картку з іншою валютою. Подвійна конвертація незабаром буде реалізована.", Response::HTTP_BAD_REQUEST);
        }

        $transaction->setReceiver($receiver);

        $currentCard->setBalance($currentCard->getBalance() - $transaction->getSumma());
        $receiverCard->setBalance($receiverCard->getBalance() + $transaction->getSumma());

        $currentDateTime = new DateTime();
        $transaction->setDate($currentDateTime);

        $this->entityManager->persist($transaction);
        $this->entityManager->flush($transaction);
        $this->entityManager->flush();
        return new JsonResponse([], Response::HTTP_CREATED);
    }
}
