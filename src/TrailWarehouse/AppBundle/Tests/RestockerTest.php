<?php

namespace TrailWarehouse\AppBundle\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use TrailWarehouse\AppBundle\Services\Restocker;

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
        $this->repo = $this->em->getRepository('TrailWarehouseAppBundle:Product');
        $this->initData();
    }

    /**
     *
     */
    public function initData()
    {
        $this->restocker = new Restocker($this->em);
        $this->old_stock_1 = $this->repo->find(1)->getStock();
        $this->old_stock_2 = $this->repo->find(2)->getStock();
        $this->maxStock = 40;
    }

    /**
     * Restock products in database and store the history
     *
     * @test
     */
    public function restock ()
    {
        $this->restocker->restock($this->repo->findAll(), $this->maxStock);
        $new_stock_1 = $this->repo->find(1)->getStock();
        $new_stock_2 = $this->repo->find(2)->getStock();

        $this->assertLessThan($new_stock_1, $this->old_stock_1, "The old stock should be lesser than the new stock");
        $this->assertLessThan($new_stock_2, $this->old_stock_2, "The old stock should be lesser than the new stock");
        $this->assertEquals($this->maxStock, $new_stock_1, "The new stock should be 87");
        $this->assertEquals($this->maxStock, $new_stock_2, "The new stock should be 87");
    }
}
