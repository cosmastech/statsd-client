<?php

namespace Cosmastech\StatsDClientAdapter\Tests\Doubles;

use DateTimeImmutable;
use InvalidArgumentException;
use Psr\Clock\ClockInterface;

class ClockStub implements ClockInterface
{
    /** @var array<int, DateTimeImmutable> */
    private readonly array $time;

    private int $currentIndex = 0;

    /**
     * @param  array<int, DateTimeImmutable>|DateTimeImmutable  $now
     */
    public function __construct(array|DateTimeImmutable $now)
    {
        $time = is_array($now) ? $now : [$now];

        if (empty($time)) {
            throw new InvalidArgumentException("Clock requires at least one DateTimeImmutable.");
        }

        $this->time = $time;
    }

    /**
     * @inheritDoc
     */
    public function now(): DateTimeImmutable
    {
        $toReturn = $this->time[$this->currentIndex];

        $this->currentIndex++;

        if ($this->currentIndex === count($this->time)) {
            $this->currentIndex = 0;
        }

        return $toReturn;
    }
}
