<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Color;
use AppBundle\Form\ColorType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ColorController extends Controller
{
    public function indexAction(Request $request)
    {
        $form = $this->createForm(ColorType::class);

        return $this->render('color/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function updateAction($id)
    {
        $color = $this->getDoctrine()
            ->getRepository(Color::class)
            ->find($id);

        if (!$color) {
            $this->addFlash(
                'error',
                'This color does not exist!'
            );

            return $this->redirectToRoute('color_index');
        }

        $form = $this->createForm(ColorType::class, $color);

        return $this->render('color/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function processUpdateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $color = $em->getRepository(Color::class)->find($id);

        if (!$color) {
            $this->addFlash(
                'error',
                'This color does not exist!'
            );

            return $this->redirectToRoute('color_index');
        }

        $validatedColor = $this->validate($request);
        $color->setName($validatedColor->getName());
        $em->flush();

        return $this->redirectToRoute('color_index');
    }

    public function postAction(Request $request)
    {
        $color = $this->validate($request);

        $em = $this->getDoctrine()->getManager();
        $em->persist($color);
        $em->flush();

        return $this->redirectToRoute('color_index');
    }

    private function validate($request)
    {
        $color = new Color();

        $form = $this->createForm(ColorType::class, $color);
        $form->handleRequest($request);

        $validator = $this->get('validator');
        $errors = $validator->validate($color);

        if (!$form->isValid() && $errors->count()) {
            return $this->render('color/index.html.twig', [
                'form' => $form->createView()
            ]);
        }

        $this->addFlash(
            'success',
            'The record was saved successfully.'
        );

        return $color;
    }
}
