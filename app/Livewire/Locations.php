<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Location;

class Locations extends Component
{
    public $locations;
    public $newLocation;

    public function mount()
    {
        $this->locations = Location::where('account_id', auth()->user()->account_id)->get();
    }

    public function addLocation()
    {
        $this->validate(['newLocation' => 'required|string']);
        Location::create([
            'name' => $this->newLocation,
            'account_id' => auth()->user()->account_id,
            'is_default' => false,
        ]);
        $this->newLocation = '';
        $this->mount();
    }

    public function setDefault($id)
    {
        Location::where('account_id', auth()->user()->account_id)->update(['is_default' => false]);
        Location::where('id', $id)->update(['is_default' => true]);
        $this->mount();
    }

    public function deleteLocation($id)
    {
        $location = Location::find($id);
        if ($location->productLocations->isEmpty()) {
            $location->delete();
        }
        $this->mount();
    }

    public function render()
    {
        return view('livewire.locations')->extends('layouts.tabler')->section('content');
    }
}
