<?php

namespace Knplabs\MarkupValidatorBundle;

use Knplabs\MarkupValidatorBundle\Validation\ProcessorInterface;
use Knplabs\MarkupValidatorBundle\Validation\ResultFactoryInterface;
use Knplabs\MarkupValidatorBundle\Validation\ResultFactory;
use Knplabs\MarkupValidatorBundle\Validation\Result;

/**
 * Markup validator
 *
 * @author Antoine Hérault <antoine.herault@gmail.com>
 */
class Validator
{
    protected $processors;
    protected $default;

    public function __construct(array $processors = array(), $default = null)
    {
        foreach ($processors as $name => $processor) {
            if (!$processor instanceof ProcessorInterface) {
                throw new \InvalidArgumentException(sprintf('Item \'%s\' of the processors array is not a processor.', $name));
            }
            $this->setProcessor($name, $processor);
        }

        $this->default = $default;
    }

    public function setProcessor($name, ProcessorInterface $processor)
    {
        $this->processors[$name] = $processor;
    }

    public function getProcessor($name)
    {
        return $this->processor[$name];
    }

    public function setDefault($name)
    {
        $this->default = $name;
    }
}
