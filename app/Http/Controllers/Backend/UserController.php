<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Privilage;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

class UserController extends Controller
{
    public function index()
    {
        $authUserId = auth()->id();

        $users = User::where('id', '!=', $authUserId)
            ->where('id', '!=', 1)
            ->whereDoesntHave('privilages', function ($query) {
                $query->where('role_id', 4);
            })
            ->get();

        $data = array(
            'title' => 'User | ',
            'datauser' => $users,
        );

        // Define the delete confirmation title and text
        $title = 'Delete User!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);
        return view('backend.user.index', $data);
    }

    public function customer()
    {
        $authUserId = auth()->id();

        $users = User::where('id', '!=', $authUserId)
            ->where('id', '!=', 1)
            ->whereHas('privilages', function ($query) {
                $query->where('role_id', 4);
            })
            ->get();

        $data = array(
            'title' => 'Customer | ',
            'datauser' => $users,
        );
        return view('backend.user.customer', $data);
    }

    public function create()
    {
        $data = array(
            'title' => 'Add User | ',
        );
        return view('backend.user.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|confirmed|min:8',
        ]);

        $user = [
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ];

        User::create($user);
        Alert::success('Success', 'User created successfully.');
        return redirect()->route('user.index');
    }

    public function show(User $user)
    {
        $data = array(
            'title' => 'View User | ',
        );
        return view('backend.user.show', $data);
    }

    public function edit(User $user)
    {
        $data = array(
            'title' => 'Edit User | ',
            'user' => $user,
        );
        return view('backend.user.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            // 'password' => 'nullable|string|confirmed|min:8',
        ]);

        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->email = $request->email;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        Alert::success('Success', 'User updated successfully.');

        return redirect()->route('user.index');
    }

    public function destroy(User $user)
    {
        $user->delete();
        Alert::success('Success', 'user deleted successfully.');

        return redirect()->route('user.index');
    }

    public function resetPassword($id)
    {
        $user = User::findOrFail($id);

        // Generate a new password
        $newPassword = '12345678';

        // Update the user's password
        $user->password = Hash::make($newPassword);
        $user->save();

        Alert::success('Success', 'password reset successfully.');
        return redirect()->route('user.index');
    }

    public function regis(Request $request)
    {
        $request->validate([
            'name1' => 'required|string|max:255',
            'phone1' => 'string|max:255',
            'email1' => 'required|string|email|max:255|unique:users,email',
            'password1' => 'required|string|min:8',
        ]);

        User::create([
            'name' => $request->name1,
            'phone' => $request->phone1,
            'email' => $request->email1,
            'password' => Hash::make($request->password1),
        ]);

        $user = User::where('email', $request->email1)->first();

        if ($user) {
            // Insert into Privilage table
            $prv = [
                'role_id' => 4,
                'user_id' => $user->id,
            ];
            Privilage::create($prv);
        }

        Alert::success('Success', 'Register customer successfully.')
            ->persistent(true)
            ->html('<b>Now you can login!!</b>')
            ->autoClose(5000);
        return redirect()->route('signin');
    }
}
