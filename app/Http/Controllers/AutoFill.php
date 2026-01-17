<?php

namespace App\Http\Controllers;

use App\Models\Action;
use Illuminate\Http\Request;

class AutoFill extends Controller
{
    public function getList($table, $search)
    {
        switch ($table) {
            case 'Actions':
                return response()->json(Action::select('action')->where('action', 'LIKE', "%$search%")->get()->unique());
            case 'Groups':
                return response()->json(Action::select('group_name')->where('group_name', 'LIKE', "%$search%")->get()->unique());
        }
    }
}
