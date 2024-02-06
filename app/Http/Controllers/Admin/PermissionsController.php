<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PermissionRequest;

class PermissionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Role $role = null)
    {
        $permissions =  Permission::get()->groupBy('group');
        $search = $request->input('q');
        return view('permissions.index', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PermissionRequest $request)
    {
        Permission::create($request->input());

        return redirect()->route('admin.permissions')->withSuccess(__('admin.permissionCreated'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $roleEdit = Role::find($request->id);
        $permissions =  Permission::get()->groupBy('group');

        $view =  view('roles.partials.role_form', compact('roleEdit'))->render();

        return response()->json(['success' => 1, 'message' => "hello", 'view' => $view], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $roleEdit = Role::find($request->id);
        foreach ($this->checkBoxes as $key => $value) {
            if (!$request->$key) {
                $request->request->set($key, '0');
            }
        }
        $roleEdit->update($request->except('permissions'));

        return redirect()->route('admin.roles')->withSuccess(__('admin.roleUpdated'));
    }
}
