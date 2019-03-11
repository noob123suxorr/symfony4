<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Model;
use AppBundle\Form\ModelType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ModelController extends Controller
{
    public function indexAction(Request $request)
    {
        $form = $this->createForm(ModelType::class);

        return $this->render('model/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function updateAction($id)
    {
        $model = $this->getDoctrine()
            ->getRepository(Model::class)
            ->find($id);

        if (!$model) {
            $this->addFlash(
                'error',
                'This model does not exist!'
            );

            return $this->redirectToRoute('model_index');
        }

        $form = $this->createForm(ModelType::class, $model);

        return $this->render('model/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function processUpdateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $model = $em->getRepository(Model::class)->find($id);

        if (!$model) {
            $this->addFlash(
                'error',
                'This model does not exist!'
            );

            return $this->redirectToRoute('model_index');
        }

        $validatedModel = $this->validate($request);
        $model->setName($validatedModel->getName());
        $em->flush();

        return $this->redirectToRoute('model_index');
    }

    public function postAction(Request $request)
    {
        $model = $this->validate($request);

        $em = $this->getDoctrine()->getManager();
        $em->persist($model);
        $em->flush();

        return $this->redirectToRoute('model_index');
    }

    private function validate($request)
    {
        $model = new Model();

        $form = $this->createForm(ModelType::class, $model, []);
        $form->handleRequest($request);

        $validator = $this->get('validator');
        $errors = $validator->validate($model);

        if (!$form->isValid() && $errors->count()) {
                return $this->render('model/index.html.twig', [
                'form' => $form->createView()
            ]);
        }

        $this->addFlash(
            'success',
            'The record was saved successfully.'
        );

        return $model;
    }
}
