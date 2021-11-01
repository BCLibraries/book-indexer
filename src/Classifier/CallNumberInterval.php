<?php

namespace Bclib\GetBooksFromAlma\Classifier;

use Danon\IntervalTree\Interval\IntervalInterface;

class CallNumberInterval implements IntervalInterface
{

    private CallNumber $low;
    private CallNumber $high;

    public function __construct($low, $high)
    {
        $this->low = $low;
        $this->high = $high;
    }

    /**
     * @param CallNumber[] $interval
     * @return CallNumberInterval
     */
    public static function fromArray($interval): CallNumberInterval
    {
        if (count($interval) !== 2) {
            throw new \InvalidArgumentException('Wrong interval array');
        }
        return new self($interval[0], $interval[1]);
    }

    public function getLow(): CallNumber
    {
        return $this->low;
    }

    public function getHigh(): CallNumber
    {
        return $this->high;
    }

    /**
     * @param CallNumberInterval $otherInterval
     * @return bool
     */
    public function equalTo($otherInterval): bool
    {
        return $this->getLow()->isEqualTo($otherInterval->getLow()) && $this->getHigh()->isEqualTo($otherInterval->getHigh());
    }

    /**
     * @param CallNumberInterval $otherInterval
     * @return bool
     */
    public function lessThan($otherInterval): bool
    {
        if ($this->getLow()->isLessThan($otherInterval->getLow())) {
            return true;
        }

        if ($otherInterval->getLow()->isLessThan($this->getLow())) {
            return false;
        }

        if ($this->getHigh()->isLessThan($otherInterval->getHigh())) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param CallNumberInterval $otherInterval
     * @return bool
     */
    public function intersect($otherInterval): bool
    {
        if ($this->getHigh()->isLessThan($otherInterval->getLow())) {
            return false;
        }

        if ($otherInterval->getHigh()->isLessThan($this->getLow())) {
            return false;
        }

        return true;
    }

    /**
     * @param CallNumberInterval $otherInterval
     * @return CallNumberInterval
     */
    public function merge($otherInterval): CallNumberInterval
    {
        $min = $this->getLow()->isLessThan($otherInterval->getLow()) ? $this->getLow() : $otherInterval->getLow();
        $max = $this->getHigh()->isLessThan($otherInterval->getHigh()) ? $otherInterval->getHigh() : $this->getHigh();
        return new CallNumberInterval($min, $max);
    }
}