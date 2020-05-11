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
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/", name="index")
     * @param UserRepository $user
     * @return Response
     */
    public function index(UserRepository $user)
    {
        return $this->render('admin/users/index.html.twig', [
            'user' => $user->findAll()
        ]);
    }

    /**
     * @param User $user
     * @param Request $request
     * @return RedirectResponse|Response
     * @Route("/edit/{user}", name="edit")
     */
    public function edit(User $user, Request $request)
    {

        $form = $this->createForm(AdminUserType::class, $user);
        $form->handleRequest($request);
        $password = $form->get('password')->getData();
        $pass = $this->getUser()->getPassword();
        if ($form->isSubmitted() && $form->isValid() && $password == $pass or $password != $pass) {
            $user->setPassword($pass);
            $this->em->persist($user);
            $this->em->flush();
            $this->addFlash('success', 'L\'utilisateur a bien été modifié');
            return $this->redirectToRoute('admin_users_index');
        }
        return $this->render('admin/users/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param User $user
     * @route("/delete/{user<\d+>}", name="delete")
     * @return Response
     */
    public function delete(User $user)
    {
        $this->em->remove($user);
        $this->em->flush();
        $this->addFlash('success', 'L\'utilisateur ' . $user->getEmail() . ' a bien été supprimé');
        return $this->redirectToRoute('admin_users_index');
    }
}
