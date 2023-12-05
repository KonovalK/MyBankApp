<?php

namespace App\Controller;

use ApiPlatform\Validator\ValidatorInterface;
use App\Entity\Card;
use App\Entity\SavingsBank;
use App\Entity\Transaction;
use App\Entity\User;
use App\Entity\Verification;
use App\Services\MailerService;
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

class SavingsBankController extends AbstractController
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
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('/get-savings-banks', name: 'get_savings_banks', methods: ['GET'])]
    public function getSavingsBanks(Request $request): JsonResponse
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->json([
                'message' => 'missing credentials',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $sortField = $request->query->get('sort');
        $sortOrder = $request->query->get('direction');

        $currentUserRepository = $this->entityManager->getRepository(User::class);

        /** @var User|null $currentUser */
        $currentUser = $currentUserRepository->findOneBy(['email' => $user->getUserIdentifier()]);

        $savingsBankRepository = $this->entityManager->getRepository(SavingsBank::class);
        $binaryId = $currentUser->getId()->toBinary();
        $savingsBanks = $savingsBankRepository->findSavingsBanks($sortField, $sortOrder, $binaryId);

        return new JsonResponse($savingsBanks, Response::HTTP_OK);
    }
    /**
     * @return JsonResponse
     */
    #[Route('/saving-bank-replenish/{savingBankNum}', name: 'saving_bank_replenish', methods: "PUT")]
    public function savingBankReplenish(Request $request, int $savingBankNum): JsonResponse
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

        $currentUserRepository = $this->entityManager->getRepository(User::class);

        /** @var User|null $currentUser */
        $currentUser = $currentUserRepository->findOneBy(['email' => $user->getUserIdentifier()]);

        $currentSavingBankRepository = $this->entityManager->getRepository(SavingsBank::class);

        /** @var SavingsBank|null $currentSavingBank */
        $currentSavingBank = $currentSavingBankRepository->findOneBy(['id' => $savingBankNum]);

        $currentCardRepository = $this->entityManager->getRepository(Card::class);
        /** @var Card|null $currentCard */
        $currentCard = $currentCardRepository->findOneBy(['cardNumber' => $requestData['card']]);

        if ($requestData['summa'] > $currentCard->getBalance()){
            return new JsonResponse("Сума поповнення не може перевищувати балансу на карті!", Response::HTTP_BAD_REQUEST);
        }

        if (!$currentUser->getSavingsBanks()->contains($currentSavingBank)) {
            return new JsonResponse("Forbidden", Response::HTTP_FORBIDDEN);
        }

        $currentSavingBank->setAmount($currentSavingBank->getAmount() + $requestData['summa']);

        $currentCard->setBalance($currentCard->getBalance() - $requestData['summa']);


        $this->entityManager->persist($currentSavingBank);
        $this->entityManager->flush($currentSavingBank);
        $this->entityManager->flush();
        return new JsonResponse([], Response::HTTP_OK);
    }

    /**
     * @return JsonResponse
     */
    #[Route('/saving-bank-withdraw/{savingBankNum}', name: 'saving_bank_withdraw', methods: "PUT")]
    public function savingBankWithdraw(Request $request, int $savingBankNum): JsonResponse
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

        $currentUserRepository = $this->entityManager->getRepository(User::class);

        /** @var User|null $currentUser */
        $currentUser = $currentUserRepository->findOneBy(['email' => $user->getUserIdentifier()]);

        $currentSavingBankRepository = $this->entityManager->getRepository(SavingsBank::class);

        /** @var SavingsBank|null $currentSavingBank */
        $currentSavingBank = $currentSavingBankRepository->findOneBy(['id' => $savingBankNum]);

        $currentCardRepository = $this->entityManager->getRepository(Card::class);
        /** @var Card|null $currentCard */
        $currentCard = $currentCardRepository->findOneBy(['cardNumber' => $requestData['card']]);

        if ($requestData['summa'] > $currentSavingBank->getAmount()){
            return new JsonResponse("На банці не достатньо коштів!", Response::HTTP_BAD_REQUEST);
        }

        if (!$currentUser->getSavingsBanks()->contains($currentSavingBank)) {
            return new JsonResponse("Forbidden", Response::HTTP_FORBIDDEN);
        }

        $currentSavingBank->setAmount($currentSavingBank->getAmount() - $requestData['summa']);

        $currentCard->setBalance($currentCard->getBalance() + $requestData['summa']);


        $this->entityManager->persist($currentSavingBank);
        $this->entityManager->flush($currentSavingBank);
        $this->entityManager->flush();
        return new JsonResponse([], Response::HTTP_OK);
    }

    /**
     * @return JsonResponse
     */
    #[Route('/delete-saving-bank/{savingBankNum}', name: 'delete_saving_bank', methods: "DELETE")]
    public function deleteSavingBank(int $savingBankNum): JsonResponse
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->json([
                'message' => 'missing credentials',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $currentUserRepository = $this->entityManager->getRepository(User::class);

        /** @var User|null $currentUser */
        $currentUser = $currentUserRepository->findOneBy(['email' => $user->getUserIdentifier()]);

        $currentSavingBankRepository = $this->entityManager->getRepository(SavingsBank::class);

        /** @var SavingsBank|null $currentSavingBank */
        $currentSavingBank = $currentSavingBankRepository->findOneBy(['id' => $savingBankNum]);

        if (!$currentUser->getSavingsBanks()->contains($currentSavingBank)) {
            return new JsonResponse("Forbidden", Response::HTTP_FORBIDDEN);
        }
        if ($currentSavingBank->getAmount() != 0) {
            return new JsonResponse("Не можна видалити, спочатку змініть усі гроші з банки", Response::HTTP_BAD_REQUEST);
        }

        $this->entityManager->remove($currentSavingBank);
        $this->entityManager->flush();

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }
}
