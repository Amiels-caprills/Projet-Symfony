<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class FactionController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager){}

    #[Route('/api/faction/join/{id}', methods: ['POST'])]
    public function join(Faction $faction, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        if ($user->getFaction()) {
            return $this->json(['error' => 'Vous êtes déjà dans une faction']);
        }

        $user->setFaction($faction);
        $em->flush();

        return $this->json(['message' => 'Vous avez rejoint ' . $faction->getNom()]);
    }


    #[Route('/api/faction/{id}', methods: ['DELETE'])]
    public function supresion(Faction $faction, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();


        if ($faction->getChef() !== $user && !in_array('ROLE_ADMIN', $user->getRoles())) {
            return $this->json(['error' => 'Action interdite']);
        }

        $em->remove($faction);
        $em->flush();

        return $this->json(['message' => 'Faction dissoute']);
    }

}
