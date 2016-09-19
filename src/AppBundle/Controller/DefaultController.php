<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Workflow\Exception\ExceptionInterface;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        return $this->render('default/index.html.twig', [
            'products' => $this->getDoctrine()->getRepository('AppBundle:Product')->findAll(),
        ]);
    }

    /**
     * @Route("/create_product", name="create_product")
     */
    public function createOrderAction()
    {
        $product = new Product();
        $em = $this->getDoctrine()->getManager();

        $em->persist($product);
        $em->flush();

        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("/product/{id}", name="show_product")
     */
    public function showAction(Product $product)
    {
        return $this->render('default/show.html.twig', [
            'product' => $product,
        ]);
    }

    /**
     * @Route("/apply-transition/{id}", name="article_apply_transition")
     */
    public function applyTransitionAction(Request $request, Product $product)
    {
        try {
            $this->get('workflow.order')
                ->apply($product, $request->request->get('transition'));

            $this->get('doctrine')->getManager()->flush();
        } catch (ExceptionInterface $e) {
            $this->get('session')->getFlashBag()->add('danger', $e->getMessage());
        }

        return $this->redirect(
            $this->generateUrl('show_product', ['id' => $product->getId()])
        );
    }
}
