<?php

namespace App\Http\Controllers\Admin;

use Hash;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Admin\UserRequest;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->get('q', false);
        $users = User::with('roles')
            ->when(!empty($search), function ($query) use ($search) {
                $query->search($search, null, true);
            })
            ->paginate();

        $roles = Role::all();

        return view('users.index', compact('users', 'roles'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function trashed(Request $request)
    {
        $search = $request->get('q', false);

        $users = User::when(!empty($search), function ($query) use ($search) {
            $query->onlyTrashed()->search($search, null, true);
        })
            ->when(empty($search), function ($query) {
                $query->onlyTrashed();
            })
            ->paginate();

        $roles = Role::all();

        return view('users.trashed', compact('users', 'roles', 'search'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        $data = $request->input();
        $user = User::create(
            [
                'name' => $data['name'],
                'username' => $data['username'],
                'about' => $data['about'],
                'email' => $data['email'],
                'picture' => 'default.png',
                'password' => Hash::make($data['password']),
                'status' => 1,
            ]
        );
        $user->syncRoles($request->roles);

        return redirect()->route('admin.users')->withSuccess(__('admin.userCreated'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $userEdit = User::find($request->id);
        $roles = Role::all();
        $view =  view('users.partials.user_form', compact('userEdit', 'roles'))->render();

        return response()->json(['success' => 1, 'message' => "hello", 'view' => $view], 200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request)
    {
        $data = $request->input();
        $user = User::find($request->id);
        $data_arry = [
            'name' => $data['name'],
            'username' => $data['username'],
            'about' => $data['about'],
            'email' => $data['email'],
            'picture' => 'default.png',
        ];

        if ($request->password != null || $request->password != "") {
            $data_arry['password'] = Hash::make($data['password']);
        }

        $user->update($data_arry);
        $user->syncRoles($request->roles);

        return redirect()->route('admin.users')->withSuccess(__('admin.userUpdated'));
    }

    public function statusChange($id, $status)
    {
        $user = User::find($id);
        $user->update(['status' => $status]);

        return redirect()->route('admin.users')->withSuccess(__('admin.userUpdated'));
    }

    public function destroy(User $user)
    {
        if ($user->id == Auth::id()) {
            return redirect()->back();
        }

        $user->delete();

        return redirect()->back()->withSuccess(__('admin.userDeleted'));
    }

    public function restore($user)
    {
        $user = User::withTrashed()->findOrFail($user);
        $user->restore();

        return redirect()->back()->withSuccess(__('admin.userRestored'));
    }

    public function delete($user)
    {
        if ($user == Auth::id()) {
            return redirect()->back();
        }

        $user = User::withTrashed()->findOrFail($user);
        $user->forceDelete();

        return redirect()->back()->withSuccess(__('admin.userDeleted'));
    }
}
