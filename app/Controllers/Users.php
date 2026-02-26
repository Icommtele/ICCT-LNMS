<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\PermissionModel;



class Users extends BaseController
{
    public function index()
    {


$db = \Config\Database::connect();

$users = $db->query("CALL GetActiveUsersWithModules()")
                ->getResultArray();

    $db->close();

    foreach ($users as &$u) {
        $u['modules'] = $u['modules']
            ? explode(',', $u['modules'])
            : [];
    }

    return view('users', [
        'active' => 'users',
        'title'  => 'Users',
        'users'  => $users
    ]);


    }



public function create()
{
    $db = \Config\Database::connect();

    $username = $this->request->getPost('username');
    $password = password_hash(
        $this->request->getPost('password'),
        PASSWORD_BCRYPT
    );
    $role = $this->request->getPost('role');

    $query = $db->query(
        "CALL CreateUser(?, ?, ?)",
        [$username, $password, $role]
    );

    $result = $query->getRow();
    $query->freeResult();

    return redirect()->to('/users')
        ->with('success', $result->message);
}



public function update($id)
{
    $db = \Config\Database::connect();
    $role = $this->request->getPost('role');

    $query = $db->query(
        "CALL UpdateUserRole(?, ?)",
        [$id, $role]
    );

    $result = $query->getRow();
    $query->freeResult();

    return redirect()->to('/users')
        ->with('success', $result->message);
}




public function delete($id)
{
    $db = \Config\Database::connect();

    $query = $db->query(
        "CALL SoftDeleteUser(?)",
        [$id]
    );

    $result = $query->getRow();
    $query->freeResult();

    return redirect()->to('/users')
        ->with('success', $result->message);
}






}
