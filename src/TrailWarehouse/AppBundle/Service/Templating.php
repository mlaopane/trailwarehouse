<?php

namespace TrailWarehouse\AppBundle\Service;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Templating\TemplateReferenceInterface;

class Templating
{
    /**
     * @var EngineInterface
     */
    protected $engine;

    /**
     * Templating constructor.
     *
     * @param EngineInterface $engine
     */
    public function __construct(ContainerInterface $container, EngineInterface $engine)
    {
        $this->engine = $engine;
        $this->container = $container;

    }

    /**
     * @param string|TemplateReferenceInterface $name
     * @param array $parameters
     *
     * @return string
     * @throws \Twig\Error\Error
     */
    public function render($name, array $parameters = [])
    {
        return $this->container->get('templating')->render("@trail_warehouse{$name}.html.twig", $parameters);
    }

    /**
     * @param $name
     * @param array $parameters
     *
     * @return string
     * @throws \Twig\Error\Error
     */
    public function renderEmail($name, array $parameters = [])
    {
        return $this->engine->render("@trail_warehouse/Email/{$name}.html.twig", $parameters);
    }
}
