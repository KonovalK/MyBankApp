<?php

namespace App\Controller;

use ApiPlatform\Validator\ValidatorInterface;
use App\Entity\Bank;
use App\Entity\Card;
use App\Entity\CardTemplate;
use App\Entity\Transaction;
use App\Entity\User;
use App\Services\FirebaseStorageService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class CardTemplateController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private ValidatorInterface $validator;
    private DenormalizerInterface $denormalizer;
    private FirebaseStorageService $firebaseStorageService;

    public function __construct(EntityManagerInterface $entityManager, ValidatorInterface $validator, DenormalizerInterface $denormalizer, FirebaseStorageService $firebaseStorageService)
    {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
        $this->denormalizer = $denormalizer;
        $this->firebaseStorageService = $firebaseStorageService;
    }

    /**
     * @return JsonResponse
     */
    #[Route('/create-card-template', name: 'create_card_template', methods: "POST")]
    public function index(Request $request): JsonResponse
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

        /** @var CardTemplate $cardTemplate */
        $cardTemplate = $this->denormalizer->denormalize($requestData, CardTemplate::class, "array");

        $this->validator->validate($cardTemplate);

        $currentUserRepository = $this->entityManager->getRepository(User::class);

        $imageFile = $request->files->get('image');

        if (!($imageFile instanceof UploadedFile)) {
            throw new UnprocessableEntityHttpException("Invalid or missing image file");
        }

        if ($imageFile->getMimeType() !== 'image/jpeg' && $imageFile->getMimeType() !== 'image/png') {
            throw new UnprocessableEntityHttpException("Invalid image file type. Only JPEG and PNG are allowed.");
        }

        $firebaseUrl = $this->firebaseStorageService->uploadFile($imageFile, 'card_templates');

        $cardTemplate->setCardBackgroundPhoto($firebaseUrl);

        $this->entityManager->persist($cardTemplate);
        $this->entityManager->flush();

        return new JsonResponse([], Response::HTTP_OK);
    }
}
