<?php

namespace App\Controller;


use App\Entity\User;
use App\Repository\UserRepository;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


final class UseController extends AbstractController
{
    public function __construct(private UserRepository $userRepository)
    {
    }

    #[Route('/api/register', name: 'user_inscription', methods: ['POST'])]
    public function inscription(Request $request, EntityManagerInterface $em): Response
    {
        $data = json_decode($request->getContent(), true);

        if (!$data) {
            return $this->json([
                'success' => false,
                'message' => 'No data found'
            ]);
        }

        $newuser = new User();

        $newuser->setCredits($data['credits']);
        $newuser->setEmail($data['email']);
        $newuser->setPassword($data['password']);
        $newuser->setRoles($data['ROLE_USER']);
        $newuser->setDateInscription(new \DateTime('now'));
        $newuser->setUuidMinecraft($data['uuidMinecraft']);
        $newuser->setPseudoMinecraft($data['pseudoMinecraft']);

        $em->persist($newuser);

        $em->flush();

        return $this->json([

            "status" => "ok",

            "message" => "user created",

            "credits" => $newuser->getcredits(),

            "Password" => $newuser->getpassword(),

            "uuidMinecraft" => $newuser->getUuidMinecraft(),

            "PseudoMinecraft" => $newuser->getPseudoMinecraft(),

            "email" => $newuser->getEmail()

        ]);
    }

    #[Route('/api/login', name: 'user_login', methods: ['POST'])]
    public function login(Request $request, EntityManagerInterface $em): Response
    {
        $data = json_decode($request->getContent(), true);

        if (!$data) {
            return $this->json(["status" => "error", "message" => "donné vide"]);
        }

        $user = $this->userRepository->findOneBy(["email" => $data["email"]]);
        if (!$user) {
            return $this->json(["status" => "error", "message" => "user not found"]);
        }

        if (md5($data['password']) == $user->getPassword()) {

            return $this->json([
                "status" => "ok",
                "message" => "login ok",
                "result" => [
                    "PseudoMinecraft" => $user->getPseudoMinecraft(),
                    "token" => $user->getToken(),
                    "email" => $user->getEmail()
                ]]);


        }

        return $this->json(["status" => "error", "message" => "login failed, wrong password"]);


    }

    #[Route('/api/me', name: 'user_Recuperation', methods: ['GET'])]

    public function token(Request $request, EntityManagerInterface $em): Response
    {

        $token = $request->headers->get('Authorization');

        if(!$token){
            return $this->json(["status"=>"error", "message"=>"token not found"]);
        }

        $token = substr($token, 7);

        $user = $this->userRepository->findOneBy(["token"=>$token]);

        if(!$user){
            return $this->json(["status"=>"error", "message"=>"user not found"]);
        }

        return $this->json([
            "status"=>"ok",
            "message"=>"connected",
            "result"=>
            ["id"=>$user->getId(),
                "name"=>$user->getDisplayname(),
                "token"=>$user->getToken(),
                "email"=>$user->getEmail()
            ]]);

    }
}
