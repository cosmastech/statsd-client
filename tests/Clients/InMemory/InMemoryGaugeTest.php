<?php

namespace Cosmastech\StatsDClient\Tests\Clients\InMemory;

use Cosmastech\StatsDClient\Clients\InMemory\InMemoryClient;
use Cosmastech\StatsDClient\Tests\BaseTestCase;
use Cosmastech\StatsDClient\Tests\ClockStub;
use Cosmastech\StatsDClient\Tests\Doubles\TagNormalizerSpy;
use DateTimeImmutable;
use PHPUnit\Framework\Attributes\Test;

class InMemoryGaugeTest extends BaseTestCase
{
    #[Test]
    public function storesGaugeRecord()
    {
        // Given
        $stubDateTime = new DateTimeImmutable("2018-02-13 18:50:00");
        $inMemoryClient = new InMemoryClient(new ClockStub($stubDateTime));

        // When
        $inMemoryClient->gauge("gauge-stat", 23488);

        // Then
        $statsRecord = $inMemoryClient->getStats();
        self::assertCount(1, $statsRecord->gauge);

        $gaugeRecord = $statsRecord->gauge[0];
        self::assertEquals("gauge-stat", $gaugeRecord->stat);
        self::assertEquals(23488, $gaugeRecord->value);
        self::assertEquals(1, $gaugeRecord->sampleRate);
        self::assertEqualsCanonicalizing([], $gaugeRecord->tags);
        self::assertEquals($stubDateTime, $gaugeRecord->recordedAt);
    }

    #[Test]
    public function normalizesTags()
    {
        // Given
        $inMemoryClient = new InMemoryClient(new ClockStub(new DateTimeImmutable()));

        // And
        $tagNormalizerSpy = new TagNormalizerSpy();
        $inMemoryClient->setTagNormalizer($tagNormalizerSpy);

        // When
        $inMemoryClient->gauge(stat: "irrelevant", value: 1.0, tags: ["hello" => "world"]);

        // Then
        $this->assertEqualsCanonicalizing([["hello" => "world"]], $tagNormalizerSpy->getNormalizeCalls());
    }
}
