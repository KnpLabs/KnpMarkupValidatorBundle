<?php

require_once $_SERVER['SYMFONY'].'/Symfony/Component/ClassLoader/UniversalClassLoader.php';

$loader = new Symfony\Component\ClassLoader\UniversalClassLoader();
$loader->registerNamespace('Knp\\Bundle\\MarkupValidatorBundle', __DIR__.'/../../../..');
$loader->register();

