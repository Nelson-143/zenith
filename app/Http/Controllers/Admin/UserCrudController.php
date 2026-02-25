<?php

namespace app\Http\Controllers\Admin;
use Illuminate\Support\Facades\DB;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use app\Models\User;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\Log;

class UserCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;

    public function setup()
    {
        CRUD::setModel(User::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/user');
        CRUD::setEntityNameStrings('User', 'Users');
    }

    protected function setupListOperation()
    {
        CRUD::column('id')->label('ID');
        CRUD::column('name')->label('Full Name');
        CRUD::column('email')->label('Email Address');
        CRUD::column('username')->label('Username');
        CRUD::column('role')->label('Role');
        CRUD::column('status')->label('Status');
        CRUD::column('created_at')->label('Registered On');

        // Optional: Add search logic
        // $this->crud->addFilter([
        //     'type' => 'text',
        //     'name' => 'name',
        //     'label'=> 'Search Name'
        // ], false, function($value) {
        //     $this->crud->addClause('where', 'name', 'LIKE', "%$value%");
        // });
    }

    protected function setupShowOperation()
    {
        $this->setupListOperation();

        // Additional columns for detailed view
        CRUD::column('account_id')->label('Account ID');
        CRUD::column('last_login')->label('Last Active');
        CRUD::column('store_name')->label('Business Name');
        CRUD::column('store_address')->label('Business Address');
        CRUD::column('store_phone')->label('Business Phone');
    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation([
            'name' => 'required|min:2|max:255',
            'email' => 'required|email|unique:users,email',
            'username' => 'required|unique:users,username',
        ]);

        CRUD::field('name')->label('Full Name');
        CRUD::field('email')->label('Email Address');
        CRUD::field('username')->label('Username');
        CRUD::field('role')->type('select_from_array')->options([
            'user' => 'User',
            'admin' => 'Admin',
            'superadmin' => 'Super Admin'
        ]);
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    public function showLocations()
    {
        $usersWithGeo = User::whereNotNull('store_address')
            ->get()
            ->map(function($user) {
                $parts = explode(',', $user->store_address ?? '');
                $user->city = trim(end($parts) ?: 'Unknown');
                $user->country = trim(prev($parts) ?: 'Unknown');
                return $user;
            });
            Log::info(User::all()->toArray());

        $topLocations = User::select(
                \DB::raw('SUBSTRING_INDEX(store_address, ",", -1) as city'),
                \DB::raw('SUBSTRING_INDEX(SUBSTRING_INDEX(store_address, ",", -2), ",", 1) as country'),
                \DB::raw('COUNT(*) as user_count')
            )
            ->whereNotNull('store_address')
            ->groupBy('city', 'country')
            ->orderBy('user_count', 'desc')
            ->limit(10)
            ->get();
        
        return view('vendor.backpack.crud.users_map', [
            'usersWithGeo' => $usersWithGeo,
            'topLocations' => $topLocations,
            'title' => 'User Locations'
        ]);
    }
}
