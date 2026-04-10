<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Event\ItemType;
use Illuminate\Http\Request;

class ItemTypeController extends Controller
{
    public function index()
    {
        $data = ItemType::all();

        return response()->json([
            'success' => true,
            'message' => 'Item types fetched successfully!',
            'data' => $data,
        ], 200);
    }
}
