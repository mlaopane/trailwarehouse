<?php
namespace TrailWarehouse\AppBundle\Twig;

use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormView;
use Symfony\Component\DependencyInjection\ContainerInterface;

class TWExtension extends \Twig_Extension
{
    protected $container;
    protected $twig;

    public function __construct(ContainerInterface $container) {
        $this->container = $container;
        $this->twig = $container->get('twig');
    }

    protected function renderForm(string $templateName, array $data)
    {
        return $this->twig->render("form/{$templateName}.html.twig", $data);
    }
}
