<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Engine;
use AppBundle\Form\EngineType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class EngineController extends Controller
{
    public function indexAction()
    {
        $form = $this->createForm(EngineType::class);

        return $this->render('engine/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function updateAction($id)
    {
        $engine = $this->getDoctrine()
            ->getRepository(Engine::class)
            ->find($id);

        if (!$engine) {
            $this->addFlash(
                'error',
                'This engine does not exist!'
            );

            return $this->redirectToRoute('engine_index');
        }

        $form = $this->createForm(EngineType::class, $engine);

        return $this->render('engine/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function processUpdateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $engine = $em->getRepository(Engine::class)->find($id);

        if (!$engine) {
            $this->addFlash(
                'error',
                'This engine does not exist!'
            );

            return $this->redirectToRoute('engine_index');
        }

        $validatedEngine = $this->validate($request);
        $engine->setName($validatedEngine->getType());
        $em->flush();

        return $this->redirectToRoute('engine_index');
    }

    public function postAction(Request $request)
    {
        $engine = $this->validate($request);

        $em = $this->getDoctrine()->getManager();
        $em->persist($engine);
        $em->flush();

        return $this->redirectToRoute('engine_index');
    }

    private function validate($request)
    {
        $engine = new Engine();

        $form = $this->createForm(EngineType::class, $engine);
        $form->handleRequest($request);

        $validator = $this->get('validator');
        $errors = $validator->validate($engine);

        if (!$form->isValid() && $errors->count()) {
            return $this->render('engine/index.html.twig', [
                'form' => $form->createView()
            ]);
        }

        $this->addFlash(
            'success',
            'The record was saved successfully.'
        );

        return $engine;
    }
}