<div>
    <x-virtual-select
        id="supplier_id" wire:model.live="selectedSupplier" options="suppliers"
    />

    {{---
    <input id="supplier_id" type="text" wire:model.live="selectedSupplier">
    ---}}
</div>
