<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Form\SearchFormType;
use App\Repository\PropertyRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class IndexController extends AbstractController
{

    /**
     * @param PropertyRepository $repository
     * @param Request $request
     * @return Response
     * @Route("/", name="index")
     */
    public function index(PropertyRepository $repository,Request $request)
    {
        $property = $repository->findAll();
        $data = new SearchData();
        $form = $this->createForm(SearchFormType::class,$data);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $property = $repository->findSearch($data);
            //$property = $repository->getCount($data);
        }

        return $this->render('index/index.html.twig', [
            'property' => $property,
            'form'     => $form->createView()
        ]);
    }
}
