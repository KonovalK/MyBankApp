<?php

namespace App\Controller;

use ApiPlatform\Validator\ValidatorInterface;
use App\Entity\Card;
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

class VerificationController extends AbstractController
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
     * @var MailerService
     */
    private MailerService $mailerService;

    /**
     * @param EntityManagerInterface $entityManager
     * @param ValidatorInterface $validator
     * @param DenormalizerInterface $denormalizer
     * @param MailerService $mailerService
     */
    public function __construct(EntityManagerInterface $entityManager, ValidatorInterface $validator, DenormalizerInterface $denormalizer, MailerService $mailerService)
    {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
        $this->denormalizer = $denormalizer;
        $this->mailerService=$mailerService;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('/verificate-user', name: 'verificate_user', methods: ['PUT'])]
    public function verificateUser(Request $request): JsonResponse
    {
        $user = $this->getUser();

        $currentUserRepository = $this->entityManager->getRepository(User::class);
        /** @var User|null $currentUser */
        $currentUser = $currentUserRepository->findOneBy(['email' => $user->getUserIdentifier()]);

        $content = json_decode($request->getContent(), true);
        $code = $content['code'];

        $currentVerificateRepository= $this->entityManager->getRepository(Verification::class);

        /** @var Verification|null $currentVerificate */
        $currentVerificate = $currentVerificateRepository->findOneBy(['user' => $currentUser->getId()]);

        if(!($currentVerificate && $currentVerificate->getCode()==$code)){
            return new JsonResponse("Невірний код!", Response::HTTP_BAD_REQUEST);
        }

        $currentUser->setRoles(["ROLE_USER"]);

        $this->entityManager->persist($currentUser);
        $this->entityManager->flush();

        return new JsonResponse([], Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('/generate-code', name: 'generate_code', methods: ['POST'])]
    public function generateVerificationCode(Request $request): JsonResponse
    {
        $user = $this->getUser();

        $currentUserRepository = $this->entityManager->getRepository(User::class);
        /** @var User|null $currentUser */
        $currentUser = $currentUserRepository->findOneBy(['email' => $user->getUserIdentifier()]);

        $currentVerificateRepository= $this->entityManager->getRepository(Verification::class);

        $newVerification = new Verification();
        $newVerification->setUser($currentUser);

        $randomCode = rand(1000,9999);
        $newVerification->setCode($randomCode);

        $userReceiver = [
            'userEmail' => $currentUser->getEmail(),
        ];

        $verificationInfo = [
            'code' => $randomCode,
        ];

        $this->mailerService->SendVerificationMail($userReceiver, $verificationInfo);

        $this->entityManager->persist($newVerification);
        $this->entityManager->flush();

        return new JsonResponse([], Response::HTTP_CREATED);
    }

}
