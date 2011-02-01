<?php

namespace Knplabs\MarkupValidatorBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * Dependency injection container compiler pass to register markup validation
 * processors. It finds all services having the "markup_validator.processor"
 * tag and adds them to the validator
 *
 * @author Antoine Hérault <antoine.herault@gmail.com>
 */
class RegisterProcessorsPass implements CompilerPassInterface
{
    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        foreach ($container->findTaggedServiceIds('markup_validator.processor') as $id => $attributes) {
            if (isset($attributes[0]['alias'])) {
                $container->setAlias(sprintf('markup_validator.%s_processor', $attributes[0]['alias']), $id);
            }
        }
    }
}
