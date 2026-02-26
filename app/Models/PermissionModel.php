<?php

namespace App\Models;

use CodeIgniter\Model;

class PermissionModel extends Model
{
    protected $table = 'lnms_permissions';
    protected $allowedFields = ['user_id', 'module'];
}
