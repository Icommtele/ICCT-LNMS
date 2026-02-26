<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Auth extends Controller
{
    public function login()
    {
        helper(['form']);
        return view('login');
    }

  /*  public function doLogin()
    {
        $session = session();
        $db = \Config\Database::connect();

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $user = $db->table('lnms_users')
                   ->where('username', $username)
                   ->get()
                   ->getRow();

        if ($user && password_verify($password, $user->password)) {
            $session->set([
                'username' => $user->username,
                'role'     => $user->role,
                'loggedIn' => true
            ]);
return redirect()->to(base_url('dashboard'));
        }

        return redirect()->back()->with('error', 'Invalid login');
    }*/


public function doLogin()
{
    $session = session();
    $db = \Config\Database::connect();

    $username = $this->request->getPost('username');
    $password = $this->request->getPost('password');

    $user = $db->table('lnms_users')
               ->where('username', $username)
               ->get()
               ->getRow();

    if ($user && password_verify($password, $user->password)) {

        // Load permissions
        $permissions = $db->table('lnms_permissions')
                          ->where('user_id', $user->id)
                          ->get()
                          ->getResultArray();

        $modules = array_column($permissions, 'module');

        // Session
        $session->set([
            'user_id'  => $user->id,
            'username' => $user->username,
            'role'     => $user->role,
            'modules'  => $modules,
            'loggedIn' => true
        ]);

        return redirect()->to(base_url('dashboard'));
    }

    return redirect()->back()->with('error', 'Invalid login');
}



    public function logout()
    {
        session()->destroy();
return redirect()->to(base_url());
    }
}
