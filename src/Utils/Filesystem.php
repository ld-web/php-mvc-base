<?php

namespace App\Utils;

use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RecursiveRegexIterator;
use RegexIterator;

class Filesystem
{
  /**
   * @deprecated use Filesystem::getFqcns instead
   */
  public static function getClassNames(string $dir): array
  {
    $files = glob($dir, GLOB_BRACE);

    return array_map(fn ($filename) => pathinfo($filename, PATHINFO_FILENAME), $files);
  }

  public static function getFqcns(string $baseDir, string $namespacePrefix = ''): array
  {
    $dir = new RecursiveDirectoryIterator($baseDir, FilesystemIterator::SKIP_DOTS | FilesystemIterator::UNIX_PATHS);

    $iterator = new RegexIterator(
      new RecursiveIteratorIterator($dir),
      '/^(.+)(\.php)$/i', // Isolate php extension in $2
      RecursiveRegexIterator::REPLACE
    );

    $iterator->replacement = '$1';

    return array_map(function ($e) use ($namespacePrefix, $baseDir) {
      $cleanedElement = $namespacePrefix . str_replace($baseDir . '/', '', $e);
      return str_replace("/", "\\", $cleanedElement);
    }, iterator_to_array($iterator));
  }
}
