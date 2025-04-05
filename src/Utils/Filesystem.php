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
     * Get all the FQCNs of the classes in the given directory.
     *
     * @param string $baseDir The base directory to search for classes.
     * @param string $namespacePrefix The namespace prefix to add to the FQCNs.
     * @return string[]
     */
    public static function getFqcns(string $baseDir, string $namespacePrefix = ''): array
    {
        $dir = new RecursiveDirectoryIterator($baseDir, FilesystemIterator::SKIP_DOTS | FilesystemIterator::UNIX_PATHS);

        $iterator = new RegexIterator(
            new RecursiveIteratorIterator($dir),
            '/^(.+)(\.php)$/i', // Isolate php extension in $2
            RecursiveRegexIterator::REPLACE
        );

        $iterator->replacement = '$1';

        return array_values(
            array_map(function ($e) use ($namespacePrefix, $baseDir) {
                $cleanedElement = $namespacePrefix . str_replace($baseDir . '/', '', $e);
                return str_replace('/', '\\', $cleanedElement);
            }, iterator_to_array($iterator))
        );
    }
}
