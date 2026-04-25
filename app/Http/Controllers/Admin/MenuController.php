<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $query = Menu::with('category');

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
        $categories = Category::active()->ordered()->get();
        return view('admin.menus.index', compact('menus', 'categories'));
    }

    public function create()
    {
        $categories = Category::active()->ordered()->get();
        return view('admin.menus.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'nama' => 'required|string|max:150',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|numeric|min:0',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request->file('gambar')->store('menus', 'public');
        }

        Menu::create($validated);

        return redirect()->route('admin.menus.index')
            ->with('success', 'Menu berhasil ditambahkan.');
    }



    public function edit(Menu $menu)
    {
        $categories = Category::active()->ordered()->get();
        return view('admin.menus.edit', compact('menu', 'categories'));
    }

    public function update(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'nama' => 'required|string|max:150',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|numeric|min:0',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_available' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $validated['is_available'] = $request->boolean('is_available');
        $validated['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('gambar')) {
            // Hapus gambar lama
            if ($menu->gambar) {
                Storage::disk('public')->delete($menu->gambar);
            }
            $validated['gambar'] = $request->file('gambar')->store('menus', 'public');
        }

        $menu->update($validated);

        return redirect()->route('admin.menus.index')
            ->with('success', 'Menu berhasil diperbarui.');
    }

    public function destroy(Menu $menu)
    {
        // Cek apakah menu sudah pernah dipesan
        if (\App\Models\OrderItem::where('menu_id', $menu->id)->exists()) {
            return back()->with('error', 'Menu tidak bisa dihapus karena sudah memiliki riwayat pesanan. Silakan nonaktifkan saja menu ini.');
        }

        if ($menu->gambar) {
            Storage::disk('public')->delete($menu->gambar);
        }

        $menu->delete();

        return redirect()->route('admin.menus.index')
            ->with('success', 'Menu berhasil dihapus.');
    }
}
