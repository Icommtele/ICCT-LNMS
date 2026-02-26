<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'lnms_users';
    protected $allowedFields = ['username', 'password', 'role', 'is_active'];
}
