<?php

namespace LaminasTest\ProgressBar\Adapter;

use LaminasTest\ProgressBar\TestAsset\JsPullStub;
use PHPUnit\Framework\TestCase;

use const JSON_THROW_ON_ERROR;

class JsPullTest extends TestCase
{
    public function testJson()
    {
        $adapter = new JsPullStub();
        $adapter->notify(0, 2, 0.5, 1, 1, 'status');
        $output = $adapter->getLastOutput();

        $data = json_decode($output, true, JSON_THROW_ON_ERROR);

        $this->assertEquals(0, $data['current']);
        $this->assertEquals(2, $data['max']);
        $this->assertEquals(50, $data['percent']);
        $this->assertEquals(1, $data['timeTaken']);
        $this->assertEquals(1, $data['timeRemaining']);
        $this->assertEquals('status', $data['text']);
        $this->assertFalse($data['finished']);

        $adapter->finish();
        $output = $adapter->getLastOutput();

        $data = json_decode($output, true, JSON_THROW_ON_ERROR);

        $this->assertTrue($data['finished']);
    }
}
