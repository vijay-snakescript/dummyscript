<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use App\Models\User;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RoleRequest;

class RolesController extends Controller
{
    protected $checkBoxes = [];

    public function __construct()
    {
        $this->checkBoxes = [
            'default' => 0,
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Role $role = null)
    {
        $roles = Role::all();
        if (!$role) {
            $role = $roles->first();
        }

        $permissions =  Permission::get()->groupBy('group');

        $search = $request->input('q');
        $users = User::query()
            ->whereHas(
                'roles',
                function ($hasQuery) use ($role) {
                    $hasQuery->where('id', $role->id);
                }
            )
            ->when($search, function ($query) use ($search) {
                $query->search($search);
            })
            ->paginate();

        return view('roles.index', compact('roles', 'users', 'role', 'permissions', 'search'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RoleRequest $request)
    {
        $role = Role::create($request->except('permissions'));
        $role->syncPermissions($request->permissions);

        return redirect()->route('admin.roles')->withSuccess(__('admin.roleCreated'));
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

        $view =  view('roles.partials.role_form', compact('roleEdit', 'permissions'))->render();

        return response()->json(['success' => 1, 'view' => $view], 200);
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
        $roleEdit->syncPermissions($request->permissions);

        return redirect()->route('admin.roles')->withSuccess(__('admin.roleUpdated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        if (config('artisan.super_admin_role') == $role->id) {
            return redirect()->back();
        }

        $role->delete();

        return redirect()->back()->withSuccess(__('admin.roleDeleted'));
    }

    public function roleAction(Request $request)
    {
        $action = $request->action;
        if ($request->users && in_array($action, ['0', '1'])) {
            $role = $request->role_id;

            User::whereIn('id', $request->users)->get()->where('id', '!=', auth()->id())->each(function ($user) use ($role, $action) {
                if ($action == 1) {
                    $user->assignRole($role);
                } else {
                    $user->removeRole($role);
                }
            });
        }

        return redirect()->back();
    }

    public function getUsers(Request $request, Role $role)
    {
        $search = $request->input('q');
        $users = User::where(function ($query) use ($role) {
            $query->whereDoesntHave('roles');
            $query->OrWhereHas(
                'roles',
                function ($hasQuery) use ($role) {
                    $hasQuery->where('id', '!=', $role->id);
                }
            );
        })
            ->when($search, function ($query) use ($search) {
                $query->search($search);
            })
            ->paginate();

        $view =  view('roles.partials.users_form', compact('users', 'role'))->render();

        return response()->json(['success' => 1, 'view' => $view], 200);
    }
}
