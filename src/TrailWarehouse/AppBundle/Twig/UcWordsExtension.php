<?php
namespace TrailWarehouse\AppBundle\Twig;

class UcWordsExtension extends \Twig_Extension {
  public function getFunctions() {
    return [
        new \Twig_SimpleFunction('ucwords', 'ucwords')
    ];
  }

  public function getFilters()
  {
    return [
        new \Twig_SimpleFilter('ucwords', 'ucwords')
    ];
  }

  public function getName()
  {
    return 'ucwords_extension';
  }
}
