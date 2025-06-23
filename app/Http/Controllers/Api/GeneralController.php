<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Franchise;
use App\Models\ServicePricelist;

class GeneralController extends Controller
{
    /**
     * Return all franchises
     */
    public function index()
    {
        return response()->json(Franchise::all());
    }

    /**
     * Return a specific franchise by ID
     */
    public function show($id)
    {
        $franchise = Franchise::find($id);

        if (!$franchise) {
            return response()->json(['message' => 'Franchise not found'], 404);
        }

        return response()->json($franchise);
    }

    /**
     * Return all service pricelist entries
     */
    public function getServicePricelists()
    {
        return response()->json(ServicePricelist::all());
    }
}
