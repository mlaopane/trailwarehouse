<?php
namespace TrailWarehouse\AppBundle\Twig;

use TrailWarehouse\AppBundle\Twig\TWExtension;

class SimpleExtension extends TWExtension
{
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('ucwords', 'ucwords'),
            new \Twig_SimpleFunction('glyphicon', [$this, 'glyphicon'], ['is_safe' => ['html']]),
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

    /**
     * slug filter
     */
    public function slugFilter($str, $search = [ " ", "'", "_", "." ], $replace = "-")
    {
        return mb_strtolower(str_replace($search, $replace, (string) $str), 'UTF-8');
    }

    /**
     * price filter
     */
    public function priceFilter($price)
    {
        return "€ " . number_format($price, 2);
    }

    /**
     * glyphicon function
     *
     * @param string expression : The unique expression after the 'glyphicon'
     * example : Put 'exclamation-sign' for 'glyphicon glyphicon-exclamation-sign'
     */
    public function glyphicon(string $expression)
    {
        return $this->renderForm('glyphicon', ['expression' => $expression]);
    }
}
