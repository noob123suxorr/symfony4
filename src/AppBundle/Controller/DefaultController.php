<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Brand;
use AppBundle\Entity\Car;
use AppBundle\Entity\Color;
use AppBundle\Entity\Engine;
use AppBundle\Entity\Model;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $brand = (int) $request->request->get('brand');
        $model = (int) $request->request->get('model');
        $engine = (int) $request->request->get('engine');
        $color = (int) $request->request->get('color');
        $min = (int) $request->request->get('min');
        $max = (int) $request->request->get('max');

        $query = $this->getDoctrine()
            ->getRepository(Car::class)
            ->createQueryBuilder('c');

        if ($brand) {
            $query = $this->addField($query, 'brand', $brand);
        }

        if ($model) {
            $query = $this->addField($query, 'model', $model);
        }

        if ($engine) {
            $query = $this->addField($query, 'engine', $engine);
        }

        if ($color) {
            $query = $this->addField($query, 'color', $color);
        }

        if ($min) {
            $query->andWhere('c.price > :min')
                ->setParameter('min', $min);
        }

        if ($max) {
            $query->andWhere('c.price < :max')
                ->setParameter('max', $max);
        }

        $cars = $query->orderBy('c.price', 'DESC')
            ->getQuery()
            ->getResult();

        $maxPrice = $this->getDoctrine()
            ->getRepository(Car::class)
            ->createQueryBuilder('c')
            ->select('c.price')
            ->orderBy('c.price', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();

        $max = 0;
        if (!empty($maxPrice[0])) {
            $max = $maxPrice[0]['price'];
        }

        $brands = $this->getDoctrine()
            ->getRepository(Brand::class)
            ->findAll();

        $models = $this->getDoctrine()
            ->getRepository(Model::class)
            ->findAll();

        $engines = $this->getDoctrine()
            ->getRepository(Engine::class)
            ->findAll();

        $colors = $this->getDoctrine()
            ->getRepository(Color::class)
            ->findAll();

        return $this->render('default/index.html.twig', [
            'cars' => $cars,
            'brands' => $brands,
            'models' => $models,
            'engines' => $engines,
            'colors' => $colors,
            'max' => $max
        ]);
    }

    private function addField($query, $name, $value)
    {
        $query->andWhere('c.' . $name . ' = :param')
            ->setParameter('param', $value);

        $this->addFlash(
            $name, $value
        );

        return $query;
    }
}
