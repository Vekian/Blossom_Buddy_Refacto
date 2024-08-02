<?php

namespace App\Repositories;

use App\Exceptions\UserNotFoundException;
use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class UserRepository implements UserRepositoryInterface {
    public function find(string $field, string $operator, $value): User
    {
        $user = User::where($field, $operator, $value)->first();
        if (! $user) {
            throw new UserNotFoundException();
        }

        return $user;
    }

    public function findAll(): Collection
    {
        $users = User::all();

        if (!$users) {
            throw new UserNotFoundException();
        }

        return $users;
    }

    public function create(Request $request): User
    {
        $user = User::create($request->all());

        if ( ! $user) {
            throw new UserNotFoundException();
        }

        return $user;
    }

    public function update(Request $request, string $common_name): User
    {

        $user = $this->find('common_name', 'LIKE', '%' . $common_name . '%');

        $user->save();
        return $user;
    }

    public function delete(int $id)
    {
        $user = $this->find('id', "=", $id);
        if (!$user) {
            throw new UserNotFoundException();
        }
        $user->delete();

    }
}