<?php

namespace App\Http\Controllers\API;

use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Exceptions\DataNotFoundException;

class RolesController extends Controller
{
    public function getRoles()
    {
        $data = Role::whereNotIn('id', [1, 6, 7])->select('id', 'name')->where('status', 1)->get();
        if (count($data) === 0) {
            throw new DataNotFoundException('Role not found.');
        }
        return response()->json([
            'data' => $data
        ], 200);
    }

}


