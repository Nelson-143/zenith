<?php

namespace app\Livewire;

use app\Models\Product;
use app\Models\Supplier;
use Livewire\Component;
use Livewire\Attributes\Rule;
use Illuminate\Support\Collection;
use Illuminate\Contracts\View\View;

class SupplierDropdown extends Component
{
    public Collection $suppliers;

    public Supplier $supplier;

    public $selectedSupplier;

    public function mount(Supplier $supplier)
    {
        if (! $this->selectedSupplier === null)
        {
            $this->selectedSupplier = array_values($supplier->pluck( 'id')->toArray());
        }

        $this->suppliers = Supplier::all()->map(function ($supplier) {
            return [
                'label' => $supplier->name,
                'value' => $supplier->id
            ];
        });
    }

    public function render(): View
    {
        return view('livewire.supplier-dropdown');
    }
}
