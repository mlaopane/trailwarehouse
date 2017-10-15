<?php

namespace TrailWarehouse\AppBundle\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use TrailWarehouse\AppBundle\Service\Restocker;

/**
 *
 * Restock products in database and store the history
 *
 * @author Mickaël LAO-PANE
 *
 */
class RestockerTest extends WebTestCase
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var EntityRepository
     */
    protected $repo;

    /**
     * @var Restocker
     */
    protected $restocker;

    /**
     *
     */
    public function __construct()
    {
        parent::__construct();



        $this->container = static::createClient()->getContainer();
        $this->em = $this->container->get('doctrine.orm.entity_manager');
        $this->rm = $this->container->get('trail_warehouse.repository_manager');
        $this->repo = [
            'product' => $this->rm->get('Product')
        ];
        $this->restocker = new Restocker($this->em);
        $this->maxStock = 50;
    }

    /**
     * @test
     */
    public function productExists()
    {
        $product = $this->repo['product']->find(1);
        $this->assertTrue($product instanceof Product, "/!\\ You should have at least one Product in the database /!\\");

        return $product;
    }

    /**
     * Restock products in database and store the history
     *
     * @test
     * @depends productExists
     */
    public function restock ($product)
    {
        $db_stock = $product->getStock();
        $this->restocker->restock($this->repo['product']->findAll(), $this->maxStock);
        $new_stock = $product->getStock();

        $this->assertEquals($new_stock, $this->maxStock, "The new stock should be " . $this->maxStock);

        if ($this->db_stock !== $new_stock) {
            $this->assertLessThan($new_stock, $this->old_stock, "The old stock should be lesser than the new stock");
        }
    }
}
