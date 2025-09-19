<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UnitController extends Controller
{
    /**
     * Load units for the authenticated user, with search and pagination.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function load(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $search = $request->query('search', '');
        $page = (int) $request->query('page', 1);
        $limit = (int) $request->query('limit', 20);

        // Call the static method on the Unit model to get available units for the user.
        $availableUnits = Unit::getAvailableForUser($user);

        // Apply search filter if provided
        if ($search) {
            $availableUnits = $availableUnits->filter(function($unit) use ($search) {
                // Assuming the Unit model has a 'unit_name' attribute.
                // Based on the closure, this seems to be the case.
                return stripos($unit->unit_name, $search) !== false;
            });
        }

        // Apply pagination
        $offset = ($page - 1) * $limit;
        $paginatedUnits = $availableUnits->slice($offset, $limit);

        return response()->json([
            // Assuming the Unit model has a getDisplayText method.
            'units' => $paginatedUnits->map(function($unit) {
                return [
                    'value' => $unit->unit_id,
                    'label' => $unit->getDisplayText(),
                    'plant_id' => $unit->plant_id,
                    'status' => $unit->status
                ];
            })->values(),
            'total' => $availableUnits->count(),
            'page' => $page,
            'has_more' => $availableUnits->count() > ($offset + $limit)
        ]);
    }
}
