<?php

namespace Knplabs\MarkupValidatorBundle\Validation;

class Error
{
    protected $line;
    protected $column;
    protected $message;

    public function __construct($line, $column, $message)
    {
        $this->line;
        $this->column;
        $this->message;
    }

    public function getLine()
    {
        return $this->line;
    }

    public function getColumn()
    {
        return $this->column;
    }

    public function getMessage()
    {
        return $this->message;
    }
}
