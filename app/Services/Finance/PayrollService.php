<?php

namespace App\Services\Finance;

class PayrollService
{
    public function calculateTotal(float $baseSalary, float $bonus, float $deduction): float
    {
        return max($baseSalary + $bonus - $deduction, 0);
    }
}
