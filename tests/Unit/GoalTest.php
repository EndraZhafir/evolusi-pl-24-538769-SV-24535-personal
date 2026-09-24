<?php

namespace Tests\Unit;

use App\Models\Saving;
use PHPUnit\Framework\TestCase;

class GoalTest extends TestCase
{
    /**
     * Uji kalkulasi persentase progres tabungan normal.
     */
    public function test_calculates_percentage_correctly(): void
    {
        $percentage = Saving::calculateProgress(2500000, 10000000);
        $this->assertEquals(25.0, $percentage);

        $percentage2 = Saving::calculateProgress(333333, 1000000);
        $this->assertEquals(33.3, $percentage2);
    }

    /**
     * Uji persentase maksimal 100% jika tabungan melebihi target.
     */
    public function test_calculates_percentage_capped_at_100(): void
    {
        $percentage = Saving::calculateProgress(15000000, 10000000);
        $this->assertEquals(100.0, $percentage);
    }

    /**
     * Uji penanganan target nol atau negatif agar tidak terjadi division by zero.
     */
    public function test_calculates_percentage_zero_target_returns_zero(): void
    {
        $percentage = Saving::calculateProgress(100000, 0);
        $this->assertEquals(0.0, $percentage);

        $percentageNegative = Saving::calculateProgress(100000, -5000);
        $this->assertEquals(0.0, $percentageNegative);
    }

    /**
     * Uji kalkulasi sisa dana yang perlu dikumpulkan.
     */
    public function test_calculates_remaining_amount_correctly(): void
    {
        $remaining = Saving::calculateRemaining(4000000, 10000000);
        $this->assertEquals(6000000.0, $remaining);
    }

    /**
     * Uji sisa target bernilai 0 ketika target sudah tercapai atau terlampaui.
     */
    public function test_remaining_amount_is_zero_when_target_reached_or_exceeded(): void
    {
        $remaining = Saving::calculateRemaining(10000000, 10000000);
        $this->assertEquals(0.0, $remaining);

        $remainingExceeded = Saving::calculateRemaining(12000000, 10000000);
        $this->assertEquals(0.0, $remainingExceeded);
    }

    /**
     * Uji deteksi status ketercapaian target tabungan (isReached).
     */
    public function test_identifies_when_goal_is_reached(): void
    {
        $savingUnreached = new Saving([
            'name' => 'Laptop',
            'target_amount' => 10000000,
            'current_amount' => 5000000,
        ]);
        $this->assertFalse($savingUnreached->isReached());

        $savingReached = new Saving([
            'name' => 'Laptop',
            'target_amount' => 10000000,
            'current_amount' => 10000000,
        ]);
        $this->assertTrue($savingReached->isReached());

        $savingExceeded = new Saving([
            'name' => 'Laptop',
            'target_amount' => 10000000,
            'current_amount' => 12000000,
        ]);
        $this->assertTrue($savingExceeded->isReached());
    }
}
