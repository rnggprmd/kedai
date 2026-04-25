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

        if ($request->filled('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('availability')) {
            $query->where('is_available', $request->availability);
        }

        $menus = $query->latest()->paginate(16)->withQueryString();

        return view('kasir.menus.index', compact('menus', 'categories'));
    }


}
