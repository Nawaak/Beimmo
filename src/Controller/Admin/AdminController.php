<?php

namespace App\Controller\Admin;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin", name="admin_")
 */
class AdminController extends AbstractController
{
    /**
     * @route("/",name="index")
     * @param EntityManagerInterface $em
     * @return Response
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function index(EntityManagerInterface $em){

        $p = $em->createQuery('SELECT count(p) FROM App\Entity\Property p')->getSingleScalarResult();
        $u = $em->createQuery('SELECT count(u) FROM App\Entity\User u')->getSingleScalarResult();

        return $this->render('admin/index.html.twig', compact('p','u'));
    }

}
