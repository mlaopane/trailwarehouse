<?php

namespace TrailWarehouse\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use TrailWarehouse\AppBundle\Entity\Item;
use TrailWarehouse\AppBundle\Entity\Cart;
use TrailWarehouse\AppBundle\Entity\Promo;
use TrailWarehouse\AppBundle\Entity\Family;
use TrailWarehouse\AppBundle\Entity\Category;
use TrailWarehouse\AppBundle\Form\PromoType;
use TrailWarehouse\AppBundle\Controller\CartController;
use TrailWarehouse\AppBundle\Service\RepositoryManager;
use TrailWarehouse\AppBundle\Service\SerializerFactory;
use Doctrine\ORM\EntityManagerInterface;

class ShopController extends Controller
{
    /**
     * Stores useful repositories
     * @var EntityRepository[]
     */
    private $repo;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * [__construct description]
     * @param SessionInterface  $session [description]
     * @param RepositoryManager $rm      [description]
     */
    public function __construct(SessionInterface $session, RepositoryManager $rm)
    {
        $this->repo = [
            'brand'    => $rm->get('Brand'),
            'category' => $rm->get('Category'),
            'family'   => $rm->get('Family'),
            'product'  => $rm->get('Product'),
            'vat'      => $rm->get('Vat'),
        ];

        $this->serializer = SerializerFactory::create($normalizers, $encoders);
        $this->initSession($session);
    }

    /**
     * [initSession description]
     * @param  SessionInterface $session [description]
     */
    private function initSession(SessionInterface $session)
    {
        if (empty($cart = $session->get('cart'))) {
            $vat = $this->repo['vat']->findOneByCountry('fr');
            $session->set('cart', (new Cart())->setVat($vat));
        }

        return $this;
    }

    /**
     * 'app_shop_search'
     */
    public function searchAction()
    {
        $data = [
            'families' => [],
        ];

        return $this->render('TrailWarehouseAppBundle:Shop:search.html.twig', $data);
    }

    /**
     * Gets the categories then render the menu
     * @param  string $active_category [description]
     */
    public function menuAction(string $active_category = '')
    {
        $data = [
            'categories'      => $this->repo['category']->findAll(['category' => 'asc']),
            'active_category' => $active_category,
        ];
        return $this->render('TrailWarehouseAppBundle:Shop:menu.html.twig', $data);
    }

    /**
     * 'app_shop_categories'
     */
    public function categoriesAction()
    {
        $data = [
            'active_category' => ['name' => 'toutes'],
            'families'        => $this->repo['family']->findAll(),
        ];

        return $this->render('TrailWarehouseAppBundle:Shop:category.html.twig', $data);
    }

    /**
     * 'app_shop_category'
     * @ParamConverter("category", options={"mapping": {"slug": "slug"}})
     *
     * @param  Category               $category [description]
     * @param  string                 $slug     [description]
     * @param  EntityManagerInterface $em       [description]
     */
    public function categoryAction(Category $category, string $slug, EntityManagerInterface $em)
    {
        $data = [
            'active_category' => $category,
            'families'        => $this->repo['family']->findByCategory($category),
        ];

        return $this->render('TrailWarehouseAppBundle:Shop:category.html.twig', $data);
    }

    /**
     * 'app_shop_family'
     *
     * @param  Family                 $family (from slug)
     * @param  EntityManagerInterface $em     [description]
     */
    public function familyAction(Family $family, EntityManagerInterface $em)
    {
        if ($family->getProducts()->count() == 0) {
            return $this->redirectToRoute('app_home');
        }

        $data = [
            'family' => $family,
            'colors' => $this->repo['product']->getColorsByFamily($family),
            'sizes'  => $this->repo['product']->getSizesByFamily($family),
        ];

        return $this->render('TrailWarehouseAppBundle:Shop:family.html.twig', $data);
    }
}
