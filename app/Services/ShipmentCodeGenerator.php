<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Shipment;
use Illuminate\Support\Facades\DB;

class ShipmentCodeGenerator
{
    /**
     * Generate a unique, sequence-consistent, concurrency-safe internal shipment number.
     * Format: {PREFIX}-{SEQUENCE} (e.g. ADJ-19001 for customer PT Adijaya with prefix ADJ).
     */
    public static function generate(?Customer $customer = null, ?string $overridePrefix = null): string
    {
        $rawPrefix = $overridePrefix ?: ($customer?->shipment_code_prefix ?: config('shipment.prefix', 'PKM'));
        $prefix = preg_replace('/[^A-Za-z0-9]/', '', (string) $rawPrefix);
        $prefix = !empty($prefix) ? strtoupper($prefix) : 'PKM';

        $pattern = "{$prefix}-%";
        $startSeq = (int) config('shipment.starting_sequence', 19001);
        $maxAttempts = 5;

        for ($attempt = 0; $attempt < $maxAttempts; $attempt++) {
            $nextSequence = DB::transaction(function () use ($pattern, $startSeq) {
                // Lock matching rows to prevent race conditions during sequence calculation
                $existingNumbers = Shipment::where('shipment_number', 'like', $pattern)
                    ->lockForUpdate()
                    ->pluck('shipment_number');

                $maxSeq = 0;
                foreach ($existingNumbers as $num) {
                    $parts = explode('-', $num);
                    $seqPart = end($parts);
                    if (is_numeric($seqPart)) {
                        $seq = (int) $seqPart;
                        if ($seq > $maxSeq) {
                            $maxSeq = $seq;
                        }
                    }
                }

                if ($maxSeq >= $startSeq) {
                    return $maxSeq + 1;
                }

                return max($maxSeq + 1, $startSeq);
            });

            $candidateCode = sprintf('%s-%d', $prefix, $nextSequence);

            // Double check safety
            if (!Shipment::where('shipment_number', $candidateCode)->exists()) {
                return $candidateCode;
            }
        }

        // Concurrency fallback
        return sprintf('%s-%d-%02d', $prefix, time() % 100000, rand(10, 99));
    }
}
