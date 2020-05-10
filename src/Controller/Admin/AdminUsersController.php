<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\AdminUserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminUsersController
 * @package App\Controller
 * @route("/admin/users", name="admin_users_")
 */
class AdminUsersController extends AbstractController
{
    /**
     * @Route("/", name="index")
     * @param UserRepository $user
     * @return Response
     */
    public function index(UserRepository $user)
    {

        return $this->render('admin/users/index.html.twig',[
            'user' => $user->findAll()
        ]);
    }

    /**
     * @param User $user
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return RedirectResponse|Response
     * @Route("/edit/{user}", name="edit")
     */
    public function edit(User $user, Request $request, EntityManagerInterface $em){

        $form = $this->createForm(AdminUserType::class,$user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($user);
            $em->flush();
            $this->addFlash('success','User modifié');
            return $this->redirectToRoute('admin_users_index');
        }
        return $this->render('admin/users/edit.html.twig',[
            'form' => $form->createView()
        ]);
    }
}
