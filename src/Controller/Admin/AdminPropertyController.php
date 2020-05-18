<?php

namespace App\Controller\Admin;

use App\Entity\Property;
use App\Form\PropertyType;
use App\Repository\PropertyRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/property", name="admin_property_")
 */
class AdminPropertyController extends AbstractController
{
    /**
     * @route("/",name="index")
     * @param PropertyRepository $repo
     * @return Response
     */
    public function property(PropertyRepository $repo): Response
    {

        $property = $repo->findAllDesc();
        return $this->render('admin/property/index.html.twig', [
            'property' => $property
        ]);
    }

    /**
     * @route("/edit/{slug<[a-z\-]+>}-{id<\d+>}",name="edit")
     * @param Property $property
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param string $slug
     * @return Response
     */
    public function edit(Property $property, Request $request, EntityManagerInterface $manager, string $slug): Response
    {

        if ($property->getSlug() != $slug) {
            return $this->redirectToRoute('admin_property_edit', [
                'slug' => $property->getSlug(),
                'id' => $property->getId()
            ], 301);
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
            return $this->redirectToRoute('admin_property_index');
        }
        return $this->render('admin/property/edit.html.twig', [
            'property' => $property,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Property $property
     * @param EntityManagerInterface $manager
     * @return JsonResponse
     * @Route("/delete/{property}", name="delete")
     */
    public function delete(Property $property, EntityManagerInterface $manager): JsonResponse
    {
        $manager->remove($property);
        $manager->flush();
        return $this->json([
            'code' => '200',
            'message' => "Le bien a bien été supprimé"
        ], 200);
    }
}
