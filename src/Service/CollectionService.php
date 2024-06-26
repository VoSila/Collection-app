<?php

namespace App\Service;

use App\Entity\CustomItemAttribute;
use App\Entity\ItemCollection;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\SluggerInterface;

readonly class CollectionService
{
    public function __construct(
        private DropboxService         $dropboxService,
        private EntityManagerInterface $entityManager,
        private FormFactoryInterface   $formFactory,
        private PaginatorInterface     $paginator,
        private Security               $security,
        private SluggerInterface       $slugger,
    )
    {
    }

    public function createCollection(Request $request, string $formType): array
    {
        $collection = new ItemCollection();
        $form = $this->createForm($formType, $collection);

        $this->sanitizeRequestData($request);

        $this->handleForm($form, $request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getImage($form, $collection);
            $this->saveCollection($collection);
            return [
                'success' => true,
                'form' => $form,
                'collection' => $collection
            ];
        }

        return [
            'success' => false,
            'form' => $form
        ];
    }

    public function editCollection(Request $request, string $formType, ItemCollection $collection): array
    {
        $form = $this->createForm($formType, $collection);
        $this->sanitizeRequestData($request);
        $this->handleForm($form, $request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getImage($form, $collection);
            $this->saveEditedCollection();
            return [
                'success' => true,
                'form' => $form,
                'collection' => $collection
            ];
        }

        return [
            'success' => false,
            'form' => $form
        ];
    }

    private function sanitizeRequestData(Request $request): void
    {
        $data = $request->get('collection');

        if (isset($data['customItemAttributes']) && is_array($data['customItemAttributes'])) {
            foreach ($data['customItemAttributes'] as &$attribute) {
                if (isset($attribute['name'])) {
                    $attribute['name'] = str_replace(' ', '_', $attribute['name']);
                }
            }
        }

        $request->request->set('collection', $data);
    }

    public function getImage(mixed $form, ItemCollection $collection): void
    {
        $imageFile = $form->get('imagePath')->getData();

        if ($imageFile) {
            $imageFileName = $this->getUniqFileName($imageFile);
            $filePath = $this->dropboxService->uploadFile($imageFile, $imageFileName);

            $collection->setImagePath($filePath);
        }
    }

    public function getUniqFileName(UploadedFile $file): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        return $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();
    }

    public function saveCollection(ItemCollection $collection): void
    {
        $user = $this->security->getUser();
        $collection->setUser($user);
        $this->entityManager->persist($collection);
        $this->entityManager->flush();
    }

    public function saveEditedCollection(): void
    {
        $this->entityManager->flush();
    }

    public function createForm(string $formType, ItemCollection $collection): FormInterface
    {
        return $this->formFactory->create($formType, $collection);
    }

    public function handleForm(FormInterface $form, Request $request): void
    {
        $form->handleRequest($request);
    }

    public function getPagination(array $collections, int $request): PaginationInterface
    {
        return $pagination = $this->paginator->paginate(
            $collections,
            $request,
            10
        );
    }

    public function checkUser()
    {
        $user = $this->security->getUser();

        if (!$user) {
            return false;
        }else{
           return $user->getId();
        }
    }

    public function getCriteriaBySorting(?string $category, int $userId): array
    {
        $criteria = ['user' => $userId];

        if ($category !== null) {
            $criteria['category'] = $category;
        }

        return $criteria;
    }

    public function getCollectionsForMain(array $criteria, string $sortField, string $sortDirection): array
    {
        return $this->entityManager->getRepository(ItemCollection::class)->findBy($criteria, [$sortField => $sortDirection]);
    }

    public function getCollectionsForShow(int $collectionId): ItemCollection
    {
        return $this->entityManager->getRepository(ItemCollection::class)->find($collectionId);
    }

    public function getCustomItemsForShow(int $collectionId): array
    {
        return $this->entityManager->getRepository(CustomItemAttribute::class)->findBy(['itemCollection' => $collectionId]);
    }
}
