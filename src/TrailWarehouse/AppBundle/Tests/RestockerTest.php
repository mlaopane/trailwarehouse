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
        $this->restocker = new Restocker($this->em);

        $this->initData();
    }

    /**
     *
     */
    public function initData()
    {
        $this->old_stock = $this->repo->find(1)->getStock();
        $this->maxStock = 50;
    }

    /**
     * Restock products in database and store the history
     *
     * @test
     */
    public function restock ()
    {
        $this->restocker->restock($this->repo->findAll(), $this->maxStock);
        $new_stock = $this->repo->find(1)->getStock();

        $this->assertEquals($new_stock, $this->maxStock, "The new stock should be " . $this->maxStock);

        if ($this->old_stock !== $new_stock) {
            $this->assertLessThan($new_stock, $this->old_stock, "The old stock should be lesser than the new stock");
        }
    }
}
