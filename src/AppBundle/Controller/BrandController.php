<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Brand;
use AppBundle\Form\BrandType;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class BrandController extends Controller
{
    public function indexAction(Request $request)
    {
        $form = $this->createForm(BrandType::class);

        return $this->render('brand/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function updateAction($id)
    {
        $brand = $this->getDoctrine()
            ->getRepository(Brand::class)
            ->find($id);

        if (!$brand) {
            $this->addFlash(
                'error',
                'This brand does not exist!'
            );

            return $this->redirectToRoute('brand_index');
        }

        $form = $this->createForm(BrandType::class, $brand);

        return $this->render('brand/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function processUpdateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $brand = $em->getRepository(Brand::class)->find($id);

        if (!$brand) {
            $this->addFlash(
                'error',
                'This brand does not exist!'
            );

            return $this->redirectToRoute('brand_index');
        }

        $validatedBrand = $this->validate($request);
        $brand->setName($validatedBrand->getName());
        $em->flush();

        $this->addFlash(
            'success',
            'The record was saved successfully.'
        );

        return $this->redirectToRoute('brand_index');
    }

    public function postAction(Request $request)
    {
        $brand = $this->validate($request);

        $em = $this->getDoctrine()->getManager();
        $em->persist($brand);

        try {
            $em->flush();

            $this->addFlash(
                'success',
                'The record was saved successfully.'
            );
        }
        catch (UniqueConstraintViolationException $e) {
            $this->addFlash(
                'error',
                'That value is already exists!'
            );
        }

        return $this->redirectToRoute('brand_index');
    }

    private function validate($request)
    {
        $brand = new Brand();

        $form = $this->createForm(BrandType::class, $brand);
        $form->handleRequest($request);

        $validator = $this->get('validator');
        $errors = $validator->validate($brand);

        if (!$form->isValid() && $errors->count()) {
            return $this->render('brand/index.html.twig', [
                'form' => $form->createView()
            ]);
        }

        return $brand;
    }
}