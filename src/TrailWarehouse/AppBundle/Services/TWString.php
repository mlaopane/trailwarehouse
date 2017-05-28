<?php

namespace TrailWarehouse\AppBundle\Services;

/**
 *
 */
class TWString
{

  public function lowercase($str) {
    return mb_strtolower($str, 'UTF-8');
  }

  public function uppercase($str) {
    return mb_strtoupper($str, 'UTF-8');
  }

  function capitalize($str) {
    $fc = $this->toUpper(mb_substr($str, 0, 1));
    return $fc.mb_substr($str, 1);
  }

}
