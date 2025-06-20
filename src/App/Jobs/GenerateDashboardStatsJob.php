<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Cache;
use Paparee\BaleInv\App\Models\Inventory;
use Paparee\BaleInv\App\Models\InventoryAssignment;
use Paparee\BaleInv\App\Models\InventoryMovement;

class GenerateDashboardStatsJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $now = now();
        $lastMonth = now()->subMonth();

        $stats = [
            'total_items' => $this->withChange(
                Inventory::count(),
                Inventory::whereMonth('created_at', $lastMonth->month)->count()
            ),

            'assignments' => $this->withChange(
                InventoryAssignment::whereReturnedAt(null)->count(),
                InventoryAssignment::whereReturnedAt(null)
                    ->whereMonth('created_at', $lastMonth->month)
                    ->count()
            ),

            'damaged_missing' => $this->withChange(
                InventoryAssignment::whereReturnCondition('damaged')->orWhere('status', 'missing')->count(),
                InventoryAssignment::whereReturnCondition('damaged')->orWhere('status', 'missing')
                    ->whereMonth('created_at', $lastMonth->month)
                    ->count()
            ),

            'stock_movement_in' => $this->withChange(
                InventoryMovement::whereDirection('in')->whereMonth('created_at', $now->month)->get()->sum('quantity'),
                InventoryMovement::whereDirection('in')->whereMonth('created_at', $lastMonth->month)->get()->sum('quantity')
            ),

            'stock_movement_out' => $this->withChange(
                InventoryMovement::whereDirection('out')->whereMonth('created_at', $now->month)->get()->sum('quantity'),
                InventoryMovement::whereDirection('out')->whereMonth('created_at', $lastMonth->month)->get()->sum('quantity')
            ),
        ];

        Cache::put('bale_cache_inv.dashboard_summary', $stats, now()->addMinutes(15));
    }

    protected function withChange(int $thisMonth, int $lastMonth): array
    {
        return [
            'value' => $thisMonth,
            'change' => $lastMonth > 0 ? round((($thisMonth - $lastMonth) / $lastMonth) * 100, 1) : null,
        ];
    }

}
