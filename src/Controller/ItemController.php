<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\ItemRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ItemController extends AbstractController
{
    public function __construct(private itemRepository $itemRepository, private UserRepository $userRepository){}

    #[Route('/api/shop', name: 'app_item', methods: ["GET"])]
    public function lister(int $id ,Request $request, ItemRepository $itemRepository): Response
    {
        $items = $this->itemRepository->findAll();

        return $this->json([
            "status" => "ok",
            "count" => count($items),
            "result" => $items


        ]);
    }

    #[Route('/api/shop/buy/{id}', name: 'item_achat', methods: ["POST"])]
    public function achat(int $id, ItemRepository $itemRepository, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $item = $itemRepository->find($id);

        if (!$item) {
            return $this->json(['error' => 'Item non trouvé']);
        }

        if ($user->getCredits() >= $item->getPrix()) {
            $user->setCredits($user->getCredits() - $item->getPrix());


            $em->flush();
            return $this->json(['status' => 'Achat réussi', 'new_balance' => $user->getCredits()]);
        }

        return $this->json(['error' => 'Crédits insuffisants']);
    }

    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $data = json_decode($request->getContent(), true);

        if ($user->getCredits() < 1000) {
            return $this->json(['error' => 'Il vous faut 1000 crédits pour créer une faction']);
        }

        $faction = new Faction();
        $faction->setNom($data['nom']);
        $faction->setDescription($data['description']);
        $faction->setPower(10);
        $faction->setChef($user);

        $user->setCredits($user->getCredits() - 1000);
        $user->setFaction($faction);

        $em->persist($faction);
        $em->flush();

        return $this->json(['message' => 'Faction créée avec succès !']);
    }


    #[Route('/api/factions', methods: ['GET'])]
    public function list(FactionRepository $repo): Response
    {
        return $this->json($repo->findAll(), ['groups' => 'faction:read']);
    }





}
