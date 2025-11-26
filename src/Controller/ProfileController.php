<?php

namespace App\Controller;

use App\Entity\Skill;
use App\Entity\UserSkillOffered;
use App\Entity\UserSkillWanted;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $this->getUser();

        $skills = $em->getRepository(Skill::class)->findAll();

        // Ajouter une compétence OFFERTE
        if ($request->query->get('add_offered')) {
            $skillId = $request->query->get('skill_id');
            $skill = $em->getRepository(Skill::class)->find($skillId);

            if ($skill) {
                $u = new UserSkillOffered();
                $u->setUser($user);
                $u->setSkill($skill);
                $em->persist($u);
                $em->flush();
            }

            return $this->redirectToRoute('app_profile');
        }

        // Ajouter une compétence VOULUE
        if ($request->query->get('add_wanted')) {
            $skillId = $request->query->get('skill_id');
            $skill = $em->getRepository(Skill::class)->find($skillId);

            if ($skill) {
                $u = new UserSkillWanted();
                $u->setUser($user);
                $u->setSkill($skill);
                $em->persist($u);
                $em->flush();
            }

            return $this->redirectToRoute('app_profile');
        }

        // Supprimer une compétence OFFERTE
        if ($request->query->get('delete_offered')) {
            $id = $request->query->get('id');
            $item = $em->getRepository(UserSkillOffered::class)->find($id);
            if ($item && $item->getUser() === $user) {
                $em->remove($item);
                $em->flush();
            }
            return $this->redirectToRoute('app_profile');
        }

        // Supprimer une compétence VOULUE
        if ($request->query->get('delete_wanted')) {
            $id = $request->query->get('id');
            $item = $em->getRepository(UserSkillWanted::class)->find($id);
            if ($item && $item->getUser() === $user) {
                $em->remove($item);
                $em->flush();
            }
            return $this->redirectToRoute('app_profile');
        }

        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'skills' => $skills,
        ]);
    }
    // Supprimer compétence offerte
#[Route('/profile/remove-offered/{id}', name: 'remove_offered')]
public function removeOffered(UserSkillOffered $offered, EntityManagerInterface $em): Response
{
    // sécurité : vérifier que l'élément appartient à l'utilisateur connecté
    if ($offered->getUser() !== $this->getUser()) {
        throw $this->createAccessDeniedException();
    }

    $em->remove($offered);
    $em->flush();

    return $this->redirectToRoute('app_profile');
}

// Supprimer compétence voulue
#[Route('/profile/remove-wanted/{id}', name: 'remove_wanted')]
public function removeWanted(UserSkillWanted $wanted, EntityManagerInterface $em): Response
{
    if ($wanted->getUser() !== $this->getUser()) {
        throw $this->createAccessDeniedException();
    }

    $em->remove($wanted);
    $em->flush();

    return $this->redirectToRoute('app_profile');
}

}
