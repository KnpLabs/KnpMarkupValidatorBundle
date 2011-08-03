<?php

namespace Knp\Bundle\MarkupValidatorBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Knp\Bundle\MarkupValidatorBundle\DependencyInjection\Compiler\RegisterProcessorsPass;

class KnpMarkupValidatorBundle extends Bundle
{
    public function registerExtensions(ContainerBuilder $container)
    {
        parent::registerExtensions($container);

        $container->addCompilerPass(new RegisterProcessorsPass());
    }

    public function getNamespace()
    {
        return __NAMESPACE__;
    }

    public function getPath()
    {
        return strtr(__DIR__, '\\', '/');
    }
}
