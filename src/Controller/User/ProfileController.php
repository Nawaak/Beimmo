<?php

namespace App\Controller\User;

use App\Entity\Property;
use App\Form\PropertyType;
use App\Repository\PropertyRepository;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserController
 * @package App\Controller
 * @Route("/profile", name="profile_")
 */
class ProfileController extends AbstractController
{
    /**
     * @Route("/", name="index")
     * @param UserRepository $repo
     * @return Response
     */
    public function index(UserRepository $repo)
    {
        $user = $repo->findBy([
            'id' => $this->getUser()
        ]);
        return $this->render('user/index.html.twig', [
            'user' => $user
        ]);
    }

    /**
     * @route("/mes-annonces", name="properties")
     * @param PropertyRepository $repository
     *
     * @return Response
     */
    public function showProperties(PropertyRepository $repository)
    {
        $p = $repository->findPropertyOnlineByUser($this->getUser());
        $p0 = $repository->findPropertyOfflineByUser($this->getUser());
        return $this->render('user/properties/index.html.twig', [
            'property' => $p,
            'property0' => $p0
        ]);
    }

    /**
     * @route("/mes-annonces/{slug<[a-zA-z\-]+>}-{property<\d+>}/edit", name="properties_edit")
     * @param PropertyRepository $repository
     * @param string $slug
     * @param Property $property
     * @param Request $request
     * @param $manager
     * @return Response
     */
    public function EditProperties(PropertyRepository $repository, string $slug, Property $property, Request $request, EntityManagerInterface $manager)
    {
        $p = $repository->findOneBy([
            'id' => $property->getId(),
            'slug' => $property->getSlug(),
            'user' => $this->getUser()
        ]);
        if (!$p) {
            throw new AccessDeniedException('Acces refusé');
        }
        if ($property->getSlug() != $slug) {
            return $this->redirectToRoute('profile_properties_edit', [
                'property' => $property->getId(),
                'slug' => $property->getSlug()
            ]);
        }

        $form = $this->createForm(PropertyType::class, $property);
        $form->handleRequest($request);
        $online = $form->get('online')->getData();
        if ($form->isSubmitted() && $form->isValid()) {
            $property->setOnline($online);
            $property->setUpdatedAt(new DateTime());
            $manager->persist($property);
            $manager->flush();
            $this->addFlash('success', 'Le bien a bien été modifié');
            return $this->redirectToRoute('profile_properties');
        }
        return $this->render('user/properties/edit.html.twig', [
            'property' => $property,
            'form' => $form->createView()
        ]);

    }

    /**
     * @route("/mes-annonces/{slug<[a-zA-z\-]+>}-{property<\d+>}/disable", name="properties_disable")
     * @param PropertyRepository $repository
     * @param string $slug
     * @param Property $property
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function disableProperties(PropertyRepository $repository, string $slug, Property $property, EntityManagerInterface $em)
    {
        $p = $repository->findOneBy([
            'id' => $property->getId(),
            'slug' => $property->getSlug()
        ]);
        $property->setOnline(0);
        $em->flush();
        $this->addFlash("success", "Votre bien a bien été supprimé");
        return $this->redirectToRoute('profile_properties', ['property' => $this->getUser()]);
    }

    /**
     * @route("/mes-annonces/{slug<[a-zA-z\-]+>}-{property<\d+>}/active", name="properties_active")
     * @param PropertyRepository $repository
     * @param string $slug
     * @param Property $property
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function activeProperties(PropertyRepository $repository, string $slug, Property $property, EntityManagerInterface $em)
    {
        $p = $repository->findOneBy([
            'id' => $property->getId(),
            'slug' => $property->getSlug()
        ]);
        $property->setOnline(1);
        $em->flush();
        $this->addFlash("success", "Votre bien a bien été restauré");
        return $this->redirectToRoute('profile_properties', ['property' => $this->getUser()]);
    }
}
