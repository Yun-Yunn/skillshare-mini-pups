<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PublicProfileController extends AbstractController
{
    #[Route('/user/{id}', name: 'app_public_profile')]
    public function show(User $user): Response
    {
        return $this->render('public_profile/show.html.twig', [
            'user' => $user,
        ]);
    }
}
