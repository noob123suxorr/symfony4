<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Car;
use AppBundle\Form\CarType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CarsController extends Controller
{
    public function indexAction(Request $request)
    {
        $form = $this->createForm(CarType::class);

        return $this->render('car/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function updateAction($id)
    {
        $car = $this->getDoctrine()
            ->getRepository(Car::class)
            ->find($id);

        if (!$car) {
            $this->addFlash(
                'error',
                'This brand does not exist!'
            );

            return $this->redirectToRoute('car_index');
        }

        $car->setImage('');
        $form = $this->createForm(CarType::class, $car);

        return $this->render('car/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function processUpdateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $car = $em->getRepository(Car::class)->find($id);

        if (!$car) {
            $this->addFlash(
                'error',
                'This brand does not exist!'
            );

            return $this->redirectToRoute('car_index');
        }

        $validatedCar = $this->validate($request);

        $car->setBrand($validatedCar->getBrand());
        $car->setModel($validatedCar->getModel());
        $car->setEngine($validatedCar->getEngine());
        $car->setColor($validatedCar->getColor());
        $car->setPrice($validatedCar->getPrice());
        $em->flush();

        $this->addFlash(
            'success',
            'The record was saved successfully.'
        );

        return $this->redirectToRoute('car_index');
    }

    public function postAction(Request $request)
    {
        $car = $this->validate($request);

        $em = $this->getDoctrine()->getManager();
        $em->persist($car);
        $em->flush();

        $this->addFlash(
            'success',
            'The record was saved successfully.'
        );

        return $this->redirectToRoute('car_index');
    }

    private function validate($request)
    {
        $car = new Car();

        $form = $this->createForm(CarType::class, $car);
        $form->handleRequest($request);

        $validator = $this->get('validator');
        $errors = $validator->validate($car);

        if (!$form->isValid() && $errors->count()) {
            return $this->render('car/index.html.twig', [
                'form' => $form->createView()
            ]);
        }

        $image = $car->getImage();
        if ($image) {
            $file = $car->getImage();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();

            $file->move(
                $this->getParameter('images_directory'),
                $fileName
            );

            $car->setImage($fileName);
        }

        return $car;
    }
}
