<?php

namespace App\Services;

use App\Models\InventoryTransaction;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class InventoryService
{
    public function record(
        Product $product,
        string $type,
        int $quantity,
        ?string $remarks = null,
        ?Model $source = null,
        array $metadata = [],
        bool $applyStockChange = true
    ): InventoryTransaction {
        $type = strtolower($type);

        if (! in_array($type, ['in', 'out'], true)) {
            throw ValidationException::withMessages([
                'type' => 'Inventory type must be either In or Out.',
            ]);
        }

        if ($quantity < 1) {
            throw ValidationException::withMessages([
                'quantity' => 'Inventory quantity must be at least 1.',
            ]);
        }

        return DB::transaction(function () use ($product, $type, $quantity, $remarks, $source, $metadata, $applyStockChange) {
            /** @var Product $lockedProduct */
            $lockedProduct = Product::query()->lockForUpdate()->findOrFail($product->id);

            $previousQuantity = (int) $lockedProduct->inventory_quantity;
            $newQuantity = $previousQuantity;

            if ($applyStockChange) {
                $delta = $type === 'in' ? $quantity : -$quantity;
                $newQuantity = $previousQuantity + $delta;

                if ($newQuantity < 0) {
                    throw ValidationException::withMessages([
                        'quantity' => "Not enough stock for {$lockedProduct->name}. Available quantity is {$previousQuantity}.",
                    ]);
                }

                $lockedProduct->forceFill([
                    'inventory_quantity' => $newQuantity,
                ])->save();
            }

            return InventoryTransaction::create([
                'store_id' => $lockedProduct->store_id,
                'product_id' => $lockedProduct->id,
                'type' => $type,
                'quantity' => $quantity,
                'previous_quantity' => $previousQuantity,
                'new_quantity' => $newQuantity,
                'remarks' => $remarks,
                'source_type' => $source ? $source->getMorphClass() : null,
                'source_id' => $source?->getKey(),
                'metadata' => $metadata,
            ]);
        });
    }
}
