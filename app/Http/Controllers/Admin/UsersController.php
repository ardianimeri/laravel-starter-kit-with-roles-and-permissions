<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Client\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $authUser = auth()->user();
        $search = $request->input('search');

        $users = User::query()->whereDoesntHave('roles', function ($query) {
            $query->where('name', 'owner');
        })->when(! $authUser->hasRole('super-admin'), function ($query) {
            $query->whereDoesntHave('roles', function ($q) {
                $q->where('name', 'super-admin');
            });
        })
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%'.$search.'%')
                        ->orWhere('email', 'like', '%'.$search.'%')
                        ->orWhere('phone', 'like', '%'.$search.'%');
                });
            })
            ->with('roles')
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('admin/users/Index', [
            'users' => $users,
            'isOwners' => false,
            'filters' => [
                'search' => $search,
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): Response
    {
        $type = $request->query('type');

        $roles = Role::query()
            ->when($type === 'owner', function ($q) {
                $q->where('name', 'owner');
            })
            ->when($type !== 'owner', function ($q) {
                $q->whereNotIn('name', ['owner', 'super-admin']);
            })
            ->get();

        return Inertia::render('admin/users/Create', [
            'roles' => $roles,
            'selectedType' => $type,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $role = Role::findById($data['role']);

        // Auto-generate email for owners if not provided
        $email = $data['email'] ?? null;
        if ($role->name === 'owner' && empty($email)) {
            $email = 'owner_'.uniqid().'@noemail.local';
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => $email,
            'phone' => $data['phone'],
            'country_code' => $data['country_code'],
            'password' => ! empty($data['password']) ? Hash::make($data['password']) : null,
        ]);

        $user->assignRole($role);

        // If request is from modal, return back instead of redirecting to index
        if ($request->query('from_modal')) {
            return back()->with('success', 'User created successfully.');
        }

        // Redirect based on user role
        if ($role->name === 'owner') {
            return redirect()->route('admin.users.owners')
                ->with('success', 'User created successfully.');
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user): Response
    {
        $user->load('roles');
        $authUser = auth()->user();
        $rolesQuery = Role::query();

        if (! $authUser->hasRole('super-admin')) {
            $rolesQuery->where('name', '!=', 'super-admin');
        }

        if ($user->hasRole('owner')) {
            $rolesQuery->where('name', 'owner');
        } else {
            $rolesQuery->where('name', '!=', 'owner');
        }

        $roles = $rolesQuery->get();

        return Inertia::render('admin/users/Edit', [
            'user' => array_merge($user->toArray(), [
                'role' => $user->roles->first(),
            ]),
            'roles' => $roles,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $data = $request->validated();

        $user->name = $data['name'];

        // Only update email if provided
        if (! empty($data['email'])) {
            $user->email = $data['email'];
        }

        // Update phone
        if (! empty($data['phone'])) {
            $user->phone = $data['phone'];
        }

        // Update country code
        if (! empty($data['country_code'])) {
            $user->country_code = $data['country_code'];
        }

        if (! empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        if ($role = Role::findById($data['role'])) {
            $user->syncRoles($role);
        }

        // Redirect based on user role
        if ($user->hasRole('owner')) {
            return redirect()->route('admin.users.owners')
                ->with('success', 'User updated successfully.');
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        if (auth()->id() === $user->id) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $isOwner = $user->hasRole('owner');

        $user->delete();

        // Redirect based on user role
        if ($isOwner) {
            return redirect()->route('admin.users.owners')
                ->with('success', 'User deleted successfully.');
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }
}
