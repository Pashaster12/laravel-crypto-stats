<?php

namespace LaravelCryptoStats\Services;

class ClassFinder
{
    //This value should be the directory that contains composer.json
    private static $appRoot;
    
    public function __construct()
    {
        self::$appRoot = base_path() . DIRECTORY_SEPARATOR;
    }

    public static function getClassesInNamespace($namespace)
    {
        $files = scandir(self::getNamespaceDirectory($namespace));

        $classes = array_map(function($file) use ($namespace) {
            return $namespace . '\\' . str_replace('.php', '', $file);
        }, $files);

        return array_filter($classes, function($possibleClass) {
            $is_abstract = true;

            if (class_exists($possibleClass)) {
                $reflection_instance = new \ReflectionClass($possibleClass);
                $is_abstract = $reflection_instance->isAbstract();
            }

            return !$is_abstract;
        });
    }

    private static function getDefinedNamespaces()
    {
        $composerJsonPath = self::$appRoot . 'composer.json';
        $composerConfig = json_decode(file_get_contents($composerJsonPath));

        //Apparently PHP doesn't like hyphens, so we use variable variables instead.
        $psr4 = "psr-4";
        return (array) $composerConfig->autoload->$psr4;
    }

    private static function getNamespaceDirectory($namespace)
    {
        $composerNamespaces = self::getDefinedNamespaces();

        $namespaceFragments = explode('\\', $namespace);
        $undefinedNamespaceFragments = [];

        while ($namespaceFragments) {
            $possibleNamespace = implode('\\', $namespaceFragments) . '\\';

            if (array_key_exists($possibleNamespace, $composerNamespaces)) {
                return realpath(self::$appRoot . $composerNamespaces[$possibleNamespace] . implode('/', $undefinedNamespaceFragments));
            }

            array_unshift($undefinedNamespaceFragments, array_pop($namespaceFragments));
        }

        return false;
    }
}