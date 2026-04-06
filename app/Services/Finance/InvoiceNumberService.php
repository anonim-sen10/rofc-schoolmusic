<?php

namespace App\Services\Finance;

use App\Models\Invoice;

class InvoiceNumberService
{
    public function generate(): string
    {
        $prefix = 'INV-'.now()->format('Ymd');
        $count = Invoice::query()->where('invoice_number', 'like', $prefix.'-%')->count() + 1;

        return $prefix.'-'.str_pad((string) $count, 4, '0', STR_PAD_LEFT);
    }
}
