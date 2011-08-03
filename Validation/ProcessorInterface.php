<?php

namespace Knp\Bundle\MarkupValidatorBundle\Validation;

/**
 * Interface for the validation processors
 *
 * @author Antoine Hérault <antoine.herault@gmail.com>
 */
interface ProcessorInterface
{
    /**
     * Executes the validation of the given markup and returns an array of
     * messages
     *
     * @param  string $markup
     *
     * @return array
     */
    function execute($markup);
}
