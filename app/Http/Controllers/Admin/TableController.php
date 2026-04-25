<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Table;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class TableController extends Controller
{
    /**
     * Download QR Sticker as PDF
     */
    public function downloadQrPdf(Table $table)
    {
        $pdf = Pdf::loadView('admin.tables.qr_pdf', compact('table'))
                  ->setPaper([0, 0, 450, 600], 'portrait');

        return $pdf->download('QR_' . str_replace(' ', '_', $table->nama_meja) . '.pdf');
    }

    public function index()
    {
        $tables = Table::withCount(['orders' => function ($q) {
            $q->whereDate('created_at', today());
        }])->get();

        return view('admin.tables.index', compact('tables'));
    }

    public function create()
    {
        return view('admin.tables.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_meja' => 'required|string|max:20|unique:tables,kode_meja',
            'nama_meja' => 'required|string|max:50',
            'kapasitas' => 'required|integer|min:1|max:20',
        ]);

        $table = Table::create($validated);

        return redirect()->route('admin.tables.index')
            ->with('success', 'Meja berhasil ditambahkan. QR Token: ' . $table->qr_token);
    }

    public function show(Table $table)
    {
        $table->load(['orders' => function ($q) {
            $q->latest()->take(20);
        }]);

        return view('admin.tables.show', compact('table'));
    }

    public function edit(Table $table)
    {
        return view('admin.tables.edit', compact('table'));
    }

    public function update(Request $request, Table $table)
    {
        $validated = $request->validate([
            'kode_meja' => 'required|string|max:20|unique:tables,kode_meja,' . $table->id,
            'nama_meja' => 'required|string|max:50',
            'kapasitas' => 'required|integer|min:1|max:20',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $table->update($validated);

        return redirect()->route('admin.tables.index')
            ->with('success', 'Meja berhasil diperbarui.');
    }

    public function destroy(Table $table)
    {
        if ($table->orders()->whereNotIn('status', ['completed', 'cancelled'])->exists()) {
            return back()->with('error', 'Meja tidak bisa dihapus karena masih ada pesanan aktif.');
        }

        $table->delete();

        return redirect()->route('admin.tables.index')
            ->with('success', 'Meja berhasil dihapus.');
    }
}
