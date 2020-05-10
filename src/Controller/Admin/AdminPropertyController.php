<?php

namespace App\Controller\Admin;

use App\Entity\Property;
use App\Form\PropertyType;
use App\Repository\PropertyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function property(PropertyRepository $repo){

        $property = $repo->findAllDesc();
        return $this->render('admin/property/index.html.twig',[
            'property' => $property
        ]);
    }

    /**
     * @route("/edit/{property}",name="edit")
     * @param Property $property
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function edit(Property $property, Request $request, EntityManagerInterface $manager): Response{

        $form = $this->createForm(PropertyType::class,$property);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $property->setUpdatedAt(new \DateTime());
            $manager->persist($property);
            $manager->flush();
            $this->addFlash('success','Le bien a bien été modifié');
            return $this->redirectToRoute('admin_property');
        }
        return $this->render('admin/property/edit.html.twig',[
            'property' => $property,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Property $property
     * @param EntityManagerInterface $manager
     * @return Response
     * @Route("/delete/{property}", name="delete")
     */
    public function delete(Property $property, EntityManagerInterface $manager): Response{
        $manager->remove($property);
        $manager->flush();
        $this->addFlash('success','Le bien a bien été supprimé');
        return $this->redirectToRoute('admin_property');
    }
}
