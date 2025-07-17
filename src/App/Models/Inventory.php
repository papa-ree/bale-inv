<?php

namespace Paparee\BaleInv\App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Paparee\BaleInv\App\Traits\HasInventory;

class Inventory extends Model
{
    use HasUuids;
    
    protected $guarded = ['id'];
    protected $connection = 'inv';

    public static function category()
    {
        $category = collect([
            ['name' => 'hardware'],
            ['name' => 'software'],
            ['name' => 'other'],
        ]);

        return $category;
    }

    public function movements()
    {
        return $this->hasMany(InventoryMovement::class);
    }

    public function setStock(int $quantity, ?string $note = null)
    {
        return $this->opname($quantity, $note);
    }

    public function distributeStock(int $quantity, $contact_location = null, $assign_contact = null, string $condition, $status = null, ?string $note = null)
    {
        return $this->distribute($quantity, $contact_location, $assign_contact, $condition, $status, $note);
    }

    public function increaseStock(int $quantity, ?string $note = null)
    {
        return $this->adjust($quantity, 'in', $note);
    }

    public function decreaseStock(int $quantity, ?string $note = null)
    {
        return $this->adjust($quantity, 'out', $note);
    }

    public function returnItem(?string $note = null)
    {
        return $this->return($note);
    }

    public function adjust(int $quantity, string $direction, ?string $note = null)
    {
        if (!in_array($direction, ['in', 'out'])) {
            throw new \InvalidArgumentException("Direction harus 'in' atau 'out'");
        }

        $this->movements()->create([
            'type' => 'adjustment',
            'direction' => $direction,
            'quantity' => abs($quantity),
            'note' => $note ?? 'Penyesuaian stok',
            'user_uuid' => Auth::user()->uuid,
        ]);

        $this->stock += $direction === 'in' ? $quantity : -$quantity;
        $this->save();
    }

    public function opname(int $targetQuantity, ?string $note = null)
    {
        $diff = $targetQuantity - $this->stock;

        if ($diff === 0) return; // tidak perlu perubahan

        $this->movements()->create([
            'type' => 'opname',
            'direction' => $diff > 0 ? 'in' : 'out',
            'quantity' => abs($diff),
            'note' => $note ?? "Stock opname to {$targetQuantity}",
        ]);

        $this->stock = $targetQuantity;
        $this->save();
    }

    public function distribute(int $quantity, $contact_location = null, $assign_contact = null, string $condition, $status = null, ?string $note = null)
    {
        DB::beginTransaction();

        try {
            $movement = $this->movements()->create([
                'type' => 'distribution',
                'direction' => 'out',
                'quantity' => $quantity,
                'note' => $note ?? 'distributed',
            ]);

            $this->stock += -$quantity;

            $this->save();

            for ($i=0; $i < $quantity; $i++) {
                $this->assignItem($movement->id, $contact_location, $assign_contact, $condition, $status, $note, Auth::user()->uuid);
            }

            DB::commit();

        } catch (\Throwable $th) {
            DB::rollBack();
            echo $th->getMessage();
        }
    }

    protected function assignItem($id, $contact_location, $assign_contact, $assign_condition, $status, $note, $user)
    {
        $assign = InventoryAssignment::create([
            'inventory_movement_id' => $id,
            'contact_location' => $contact_location,
            'type' => 'distribution',
            'assign_contact' => $assign_contact,
            'assign_condition' => $assign_condition,
            'assigned_at' => now(),
            'status' => $status,
        ]);

        $this->assignItemDetail($assign->id);
        $this->assignItemLog($assign->id, 'distribution', $contact_location, $assign_contact, $assign_condition, $status, $note, $user);
    }

    protected function assignItemDetail($id)
    {
        InventoryAssignmentItemDetail::create([
            'inventory_assignment_id' => $id,
        ]);
    }

    public function assignItemLog($id, $type, $location, $contact, $condition, $status, $note, $user)
    {
        InventoryAssignmentLog::create([
            'inventory_assignment_id' => $id,
            'type' => $type,
            'location' => $location,
            'contact' => $contact,
            'condition' => $condition,
            'status' => $status,
            'user_uuid' => $user,
            'note' => $note,
        ]);
    }

    public function return(?string $note = null)
    {
        DB::beginTransaction();

        try {
            $this->movements()->create([
                'type' => 'return',
                'direction' => 'in',
                'quantity' => 1,
                'note' => $note ?? 'returned',
            ]);

            $this->stock += 1;

            $this->save();

            DB::commit();

        } catch (\Throwable $th) {
            DB::rollBack();
            echo $th->getMessage();
        }
    }
}
