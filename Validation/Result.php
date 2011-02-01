<?php

namespace Knplabs\MarkupValidatorBundle\Validation;

/**
 * Validation result
 *
 * @author Antoine Hérault <antoine.herault@gmail.com>
 */
class Result
{
    protected $errors;
    protected $warnings;

    /**
     * Constructor
     *
     * @param  array $errors
     * @param  array $warnings
     */
    public function __construct(array $errors = array(), array $warnings = array())
    {
        $this->errors = $errors;
        $this->warnings = $warnings;
    }

    /**
     * Indicates whether the result is valid (has no error)
     *
     * @return boolean
     */
    public function isValid()
    {
        return !$this->hasError();
    }

    /**
     * Returns all errors
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Returns the number of errors
     *
     * @return integer
     */
    public function getNumErrors()
    {
        return count($this->errors);
    }

    /**
     * Indicates whether the result has an error
     *
     * @return boolean
     */
    public function hasError()
    {
        return $this->getNumErrors() > 0;
    }

    /**
     * Adds the given error
     *
     * @param  Error $error
     */
    public function addError(Error $error)
    {
        $this->errors[] = $error;
    }

    /**
     * Returns all warnings
     *
     * @return array
     */
    public function getWarnings()
    {
        return $this->warnings;
    }

    /**
     * Returns the number of warnings
     *
     * @return interger
     */
    public function getNumWarnings()
    {
        return count($this->warnings);
    }

    /**
     * Indicates whether the result has a warning
     *
     * @return boolean
     */
    public function hasWarning()
    {
        return $this->getNumWarnings() > 0;
    }

    /**
     * Adds the given warning
     *
     * @param  Warning $warning
     */
    public function addWarning(Warning $warning)
    {
        $this->warnings[] = $warning;
    }
}
