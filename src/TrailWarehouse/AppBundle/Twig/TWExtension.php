<?php
namespace TrailWarehouse\AppBundle\Twig;

class TWExtension extends \Twig_Extension
{

  public function getFunctions() {
    return [
        new \Twig_SimpleFunction('ucwords', 'ucwords'),
    ];
  }

  public function getFilters()
  {
    return [
        new \Twig_SimpleFilter('ucwords', 'ucwords'),
        new \Twig_SimpleFilter('slug', [$this, 'slugFilter']),
        new \Twig_SimpleFilter('price', [$this, 'priceFilter']),
    ];
  }

  public function slugFilter($str, $search = [ " ", "'", "_", "." ], $replace = "-")
  {
    return mb_strtolower(str_replace($search, $replace, (string) $str), 'UTF-8');
  }

  public function priceFilter($price)
  {
    return "€ " . number_format($price, 2);
  }

  public function getName()
  {
    return 'tw_extension';
  }
}
