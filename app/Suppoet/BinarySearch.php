<?php


  namespace App\Suppoet;

class BinarySearch {

  public static function search(array $arrSort, string $target, string $key = 'name') : ?array {

  $low = 0;
  $high = count($arrSort) - 1;

  while($low <= $high) {
      $mid = \intdiv($low + $high, 2);
      $cmp = \strcasecmp($arrSort[$mid][$key], $target);
      
      if($cmp === 0) {
        return $arrSort[$mid];
      }
      $cmp < 0 ? $low = $mid + 1 : $high = $mid - 1;
  }
  return null;

}
}