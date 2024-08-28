<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;
use Illuminate\Support\Facades\Validator;

class MenuController extends Controller
{
    // Get all menus hierarchically
    public function index()
    {
        // Fetch all menu items at once
        $menus = Menu::all();

        // Convert the flat list of menus to a hierarchical structure
        $menuHierarchy = $this->buildMenuHierarchy($menus);

        return response()->json([
            'message' => 'Get Menus List Successfully',
            'data' => $menuHierarchy,
            'code' => 200
        ], 200);
    }

    // Get specific menu (with its depth and root item)
    public function show($menu_id)
    {
        $menu = Menu::with('parent', 'children')->where('menu_id', $menu_id)->firstOrFail();
        return response()->json([
            'message' => 'Show Menus Details Successfully',
            'data' => $menu,
            'code' => 200
        ], 200);
    }

    // Add a new menu item
    public function store(Request $request)
    {
        $validator = $request->validate([
            'name' => 'required|string|max:255',
            'parent_data' => [
                'nullable',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    if (!empty($value) && !Menu::where('name', $value)->exists()) {
                        $fail('Add Valid Parent Name');
                    }
                }
            ],
            'depth' => 'required|integer',
        ]);

        $menu = Menu::create($request->only([
            'name',
            'parent_data',
            'depth',
        ]));

        return response()->json([
            'message' => 'Menus Created Successfully',
            'data' => $menu,
            'code' => 200
        ], 200);
    }

    // Update a menu item
    public function update(Request $request, $menu_id)
    {
        $menu = Menu::where('menu_id', $menu_id)->firstOrFail();

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'parent_data' => [
                'nullable',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    if (!empty($value) && !Menu::where('name', $value)->exists()) {
                        $fail('Add Valid Parent Name');
                    }
                }
            ],
            'depth' => 'required|integer',
        ]);

        $menu->update($validatedData);

        return response()->json([
            'message' => 'Menu Update Successfully',
            'data' => $menu,
            'code' => 200
        ], 200);
    }

    // Delete a menu item
    public function destroy($menu_id)
    {
        $menu = Menu::where('menu_id', $menu_id)->firstOrFail();
        $menu->delete();

        return response()->json([
            'message' => 'Menu Deleted Successfully',
            'data' => $menu,
            'code' => 200
        ], 200);
    }

    private function buildMenuHierarchy($menus, $parentName = null)
    {
        $result = [];

        foreach ($menus as $menu) {
            if ($menu->parent_data == $parentName) {
                $children = $this->buildMenuHierarchy($menus, $menu->name);
                if ($children) {
                    $menu->children = $children;
                }
                $result[] = $menu;
            }
        }

        return $result;
    }
}
