<?php

namespace App\Helpers\Routes;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class RouteHelper
{
  public static function includeRouteFiles(string $folder)
  {
    // iterate thru the v1 folder recursively
    $dirs = new RecursiveDirectoryIterator($folder);

    /** @var RecursiveIteratorIterator|RecursiveDirectoryIterator $it */
    $it = new RecursiveIteratorIterator($dirs);

    // require the file in each iteration
    while ( $it->valid() ) {
      if ( ! $it->isDot()
        && $it->isFile()
        && $it->isReadable()
        && $it->getExtension() === 'php' ) {
        require $it->key();
      }

      $it->next();
    }
  }
}