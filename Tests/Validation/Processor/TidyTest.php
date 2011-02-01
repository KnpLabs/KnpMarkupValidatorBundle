<?php

namespace Knplabs\MarkupValidatorBundle\Validation\Processor;

use Knplabs\MarkupValidatorBundle\Validation\Validator;

class TidyTest extends \PHPUnit_Framework_TestCase
{
    public function testExecute()
    {
        $markup = "<!DOCTYPE html><html><head></head><body></body></html>";

        $processor = $this->getMock('Knplabs\MarkupValidatorBundle\Validation\Processor\Tidy', array(), array('Tidy'));
        $processor->expects($this->once())
            ->method('executeTidy')
            ->with($this->equalTo($markup))
            ->will($this->returnValue(array()));

        $processor->execute($markup);
    }

    public function testParseLines()
    {
        $processor = new Tidy('tidy');

        $lines = array(
            '',
            'line 1 column 10 - Warning: This is a warning',
            'some text',
            'line 10 column 20 - Error: This is an error'
        );

        $messages = $processor->parseLines($lines);

        $this->assertEquals(2, count($messages));
        $this->assertContains(array(
            'type'      => Validator::MESSAGE_TYPE_WARNING,
            'line'      => 1,
            'column'    => 10,
            'message'   => 'This is a warning'
        ), $messages);
        $this->assertContains(array(
            'type'      => Validator::MESSAGE_TYPE_ERROR,
            'line'      => 10,
            'column'    => 20,
            'message'   => 'This is an error'
        ), $messages);
    }
}
