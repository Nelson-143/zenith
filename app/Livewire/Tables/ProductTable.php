<?php

namespace app\Livewire\Tables;

use Livewire\Component;
use app\Models\Product;
use Livewire\WithPagination;

class ProductTable extends Component
{
    use WithPagination;

    public $perPage = 5;
    public $search = '';
    public $sortField = 'id';
    public $sortAsc = false;

    public function sortBy($field): void
    {
        if($this->sortField === $field) {
            $this->sortAsc = !$this->sortAsc;
        } else {
            $this->sortAsc = true;
        }
        $this->sortField = $field;
    }

    public function render()
    {
        return view('livewire.tables.product-table', [
            'products' => Product::query()
                ->where('account_id', auth()->user()->account_id) // Filter by account instead of user
                ->with(['category', 'unit', 'supplier'])
                ->search($this->search)
                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                ->paginate($this->perPage)
        ]);
    }
}