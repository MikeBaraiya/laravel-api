<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRepository
{
    /**
     * Find a user by Username
     *
     * @param  int  $id
     * @return User|null
     */
    public function findUserByUsername(string $username)
    {
        return User::where('username', $username)->first();
    }

    /**
     * Get all users
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all()
    {
        return User::all();
    }

    /**
     * Create a new user
     *
     * @param  array  $data
     * @return User
     */
    public function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'username' => $data['username'],
            'designation' => $data['designation'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    /**
     * Find a user by ID
     *
     * @param  int  $id
     * @return User|null
     */
    public function find($id)
    {
        return User::find($id);
    }

    /**
     * Update a user by ID
     *
     * @param  int  $id
     * @param  array  $data
     * @return User
     */
    public function update($id, array $data)
    {
        $user = $this->find($id);
        if (!$user) {
            return null;
        }

        // Update the user record
        $user->update($data);
        return $user;
    }

    /**
     * Delete a user by ID
     *
     * @param  int  $id
     * @return bool|null
     */
    public function delete($id)
    {
        $user = $this->find($id);

        if (!$user) {
            return false;
        }

        if ($user->is_admin == 1) {
            return false;
        }

        return $user->delete();
    }
}
