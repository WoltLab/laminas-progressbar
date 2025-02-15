<?php

namespace LaminasTest\ProgressBar;

use Laminas\ProgressBar\Exception;
use LaminasTest\ProgressBar\TestAsset\MockUp;
use LaminasTest\ProgressBar\TestAsset\Stub;
use PHPUnit\Framework\TestCase;

class ProgressBarTest extends TestCase
{
    public function testGreaterMin()
    {
        $this->expectExceptionMessage(Exception\OutOfRangeException::class);
        $this->expectExceptionMessage('$max must be greater than $min');
        $this->_getProgressBar(1, 0);
    }

    public function testPersistence()
    {
        $progressBar = $this->_getProgressBar(0, 100, 'foobar');
        $progressBar->update(25);

        $progressBar = $this->_getProgressBar(0, 100, 'foobar');
        $progressBar->update();
        $this->assertEquals(25, $progressBar->getCurrent());
    }

    public function testDefaultPercentage()
    {
        $progressBar = $this->_getProgressBar(0, 100);
        $progressBar->update(25);

        $this->assertEquals(.25, $progressBar->getPercent());
    }

    public function testPositiveToPositivePercentage()
    {
        $progressBar = $this->_getProgressBar(10, 20);
        $progressBar->update(12.5);

        $this->assertEquals(.25, $progressBar->getPercent());
    }

    public function testNegativeToPositivePercentage()
    {
        $progressBar = $this->_getProgressBar(-5, 5);
        $progressBar->update(-2.5);

        $this->assertEquals(.25, $progressBar->getPercent());
    }

    public function testNegativeToNegativePercentage()
    {
        $progressBar = $this->_getProgressBar(-20, -10);
        $progressBar->update(-17.5);

        $this->assertEquals(.25, $progressBar->getPercent());
    }

    public function testEtaCalculation()
    {
        $progressBar = $this->_getProgressBar(0, 100);

        $progressBar->sleep(3);
        $progressBar->update(33);
        $progressBar->sleep(3);
        $progressBar->update(66);

        $this->assertEquals(3, $progressBar->getTimeRemaining());
    }

    public function testEtaZeroPercent()
    {
        $progressBar = $this->_getProgressBar(0, 100);

        $progressBar->sleep(5);
        $progressBar->update(0);

        $this->assertEquals(null, $progressBar->getTimeRemaining());
    }

    public function testMissingTextIsNotNull()
    {
        $progressBar = $this->_getProgressBar(0, 100);

        $this->assertSame('', $progressBar->getText());
    }

    // @codingStandardsIgnoreStart
    protected function _getProgressBar($min, $max, $persistenceNamespace = null)
    {
        // @codingStandardsIgnoreEnd
        return new Stub(new MockUp(), $min, $max, $persistenceNamespace);
    }
}
