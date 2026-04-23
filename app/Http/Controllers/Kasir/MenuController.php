<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Category;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    /**
     * Kasir hanya bisa LIHAT menu — tidak bisa CRUD.
     */
    public function index(Request $request)
    {
        $categories = Category::active()->ordered()->get();
        $query = Menu::with('category')->active();

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $menus = $query->latest()->paginate(16);

        return view('kasir.menus.index', compact('menus', 'categories'));
    }

    public function show(Menu $menu)
    {
        $menu->load('category');
        return view('kasir.menus.show', compact('menu'));
    }
}
