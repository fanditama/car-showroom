<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserManagement extends Component
{
    use WithPagination;

    public $name, $email, $role, $password, $password_confirmation;
    public $search = '';
    public $userId;
    public $isOpen = false;
    public $showDeleteModal = false;
    public $userToDelete;

    protected $paginationTheme = 'tailwind';

    protected function rules()
    {
        return [
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $this->userId,
            'role' => 'required|in:admin,user',
            'password' => $this->userId
                ? 'nullable|string|confirmed|min:8'
                : ['required', 'string', 'confirmed', Password::defaults()],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Nama tidak boleh kosong.',
            'name.min' => 'Nama minimal harus 3 karakter.',
            'email.required' => 'Email tidak boleh kosong.',
            'role.required' => 'Role tidak boleh kosong.',
            'password.required' => 'Password tidak boleh kosong.',
            'password.min' => 'Password minimal harus 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'email.unique' => 'Email sudah terdaftar.',
        ];
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function create()
    {
        $this->reset(['name', 'email', 'role', 'password', 'password_confirmation']);
        $this->isOpen = true;
    }

    public function store()
    {
        $this->validate();

        User::updateOrCreate(['id' => $this->userId], [
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'password' => Hash::make($this->password),
        ]);

        $this->isOpen = false;
        $this->reset(['name', 'email', 'role', 'password', 'password_confirmation', 'userId']);
        session()->flash('message', $this->userId ? 'User updated successfully.' : 'User created successfully.');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role;
        $this->password = '';
        $this->password_confirmation = '';
        $this->isOpen = true;
    }

    public function confirmDelete($id)
    {
        $this->userToDelete = $id;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        User::destroy($this->userToDelete);
        $this->showDeleteModal = false;
        $this->userToDelete = null;
        session()->flash('message', 'Berhasil menghapus user.');
    }

    public function render()
    {
        $users = User::where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%')
                    ->orWhere('role', 'like', '%' . $this->search . '%')
                    ->paginate(10);
        return view('livewire.dashboard.user-management', compact('users'));
    }
}
