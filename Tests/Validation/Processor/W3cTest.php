<?php

namespace Knp\Bundle\MarkupValidatorBundle\Validation\Processor;

use Knp\Bundle\MarkupValidatorBundle\Validation\Validator;

class W3cTest extends \PHPUnit_Framework_TestCase
{
    public function testNormalizeErrorMessage()
    {
        $processor = new W3c('validator.tld');

        $errorMessage = array(
            'type'          => 'error',
            'lastLine'      => 1,
            'lastColumn'    => 10,
            'explanation'   => 'This is an error'
        );

        $this->assertEquals(array(
            'type'      => Validator::MESSAGE_TYPE_ERROR,
            'line'      => 1,
            'column'    => 10,
            'message'   => 'This is an error'
        ), $processor->normalizeMessage($errorMessage));
    }

    public function testNormalizeWarningMessage()
    {
        $processor = new W3c('validator.tld');

        $warningMessage = array(
            'type'          => 'info',
            'subtype'       => 'warning',
            'lastLine'      => 10,
            'lastColumn'    => 20,
            'explanation'   => 'This is a warning'
        );

        $this->assertEquals(array(
            'type'      => Validator::MESSAGE_TYPE_WARNING,
            'line'      => 10,
            'column'    => 20,
            'message'   => 'This is a warning'
        ), $processor->normalizeMessage($warningMessage));
    }

    public function testMergeOptions()
    {
        $processor = new W3c('validator.tld', array(
            CURLOPT_URL         => 'other.tld',
            CURLOPT_BUFFERSIZE  => 100,
            CURLOPT_TIMEOUT     => 10
        ));

        $options = $processor->mergeOptions(array(
            CURLOPT_BUFFERSIZE  => 200
        ));

        $this->assertEquals('validator.tld', $options[CURLOPT_URL]);
        $this->assertEquals(200, $options[CURLOPT_BUFFERSIZE]);
        $this->assertEquals(10, $options[CURLOPT_TIMEOUT]);
    }
}
