<?php

namespace app\Http\Controllers\Admin;

use app\Http\Requests\AdminRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use app\Models\Admin;
class AdminCrudController extends CrudController
{
    public function setup()
    {
        CRUD::setModel(Admin::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/admin');
        CRUD::setEntityNameStrings('admin', 'admins');
        
        // Add fields
        CRUD::addFields([
            [
                'name'  => 'name',
                'label' => "Name",
                'type'  => 'text',
            ],
            [
                'name'  => 'email',
                'label' => "Email",
                'type'  => 'email',
            ],
            [
                'name'  => 'password',
                'label' => "Password",
                'type'  => 'password',
            ],
            [
                'name'  => 'is_superadmin',
                'label' => "Super Admin",
                'type'  => 'checkbox',
            ]
        ]);
        
        // Add columns
        CRUD::addColumns([
            [
                'name'  => 'name',
                'label' => "Name",
            ],
            [
                'name'  => 'email',
                'label' => "Email",
            ],
            [
                'name'  => 'is_superadmin',
                'label' => "Super Admin",
                'type'  => 'boolean',
            ],
            [
                'name'  => 'last_login',
                'label' => "Last Login",
                'type'  => 'datetime',
            ]
        ]);
    }
    
    protected function setupCreateOperation()
    {
        CRUD::setValidation([
            'name' => 'required|min:2|max:255',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|min:6',
        ]);
    }
    
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
        CRUD::field('password')->hint('Leave empty to keep current password');
    }
}
