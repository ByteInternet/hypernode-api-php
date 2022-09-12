<?php

declare(strict_types=1);

namespace Hypernode\Api\Resource\Logbook;

use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class FlowTest extends TestCase
{
    public function testCreatedAtDate()
    {
        $flow = new Flow(['created_at' => '2022-09-12T09:00:00Z']);
        $this->assertInstanceOf(Carbon::class, $flow->created_at);
    }

    public function testUpdatedAtDate()
    {
        $flow = new Flow(['updated_at' => '2022-09-12T09:00:00Z']);
        $this->assertInstanceOf(Carbon::class, $flow->updated_at);
    }

    public function testIsRevertedReturnsTrue()
    {
        $flow = new Flow(['state' => 'reverted']);
        $this->assertTrue($flow->isReverted());
    }

    public function testIsRevertedReturnsFalse()
    {
        $flow = new Flow(['state' => 'running']);
        $this->assertFalse($flow->isReverted());
    }

    public function testIsCompleteReturnTrue()
    {
        $flow = new Flow(['state' => 'success']);
        $this->assertTrue($flow->isComplete());
    }

    public function testIsCompleteReturnFalse()
    {
        $flow = new Flow(['state' => 'running']);
        $this->assertFalse($flow->isComplete());
    }

    public function testIsRunningReturnsTrue()
    {
        $flow = new Flow(['state' => 'running']);
        $this->assertTrue($flow->isRunning());
    }

    public function testIsRunningReturnsFalse()
    {
        $flow = new Flow(['state' => 'success']);
        $this->assertFalse($flow->isRunning());
    }
}
