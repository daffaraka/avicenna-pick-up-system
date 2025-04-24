<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PenjemputanHarian;

class PenjemputanHarianController extends Controller
{

    public function index()
    {
        $penjemputan = PenjemputanHarian::with('siswa')->get();
        return view('dashboard.penjemputan-harian.penjemputan-index', compact('penjemputan'));
    }

    public function create()
    {
        return view('penjemputan_harian.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'field1' => 'required',
            'field2' => 'required',
            // Add other fields validation as needed
        ]);

        PenjemputanHarian::create($request->all());
        return redirect()->route('penjemputan-harian.index')
            ->with('success', 'Data created successfully.');
    }

    public function show(PenjemputanHarian $penjemputanHarian)
    {
        return view('penjemputan_harian.show', compact('penjemputanHarian'));
    }

    public function edit(PenjemputanHarian $penjemputanHarian)
    {
        return view('penjemputan_harian.edit', compact('penjemputanHarian'));
    }

    public function update(Request $request, PenjemputanHarian $penjemputanHarian)
    {
        $request->validate([
            'field1' => 'required',
            'field2' => 'required',
            // Add other fields validation as needed
        ]);

        $penjemputanHarian->update($request->all());
        return redirect()->route('penjemputan-harian.index')
            ->with('success', 'Data updated successfully.');
    }

    public function destroy(PenjemputanHarian $penjemputanHarian)
    {
        $penjemputanHarian->delete();
        return redirect()->route('penjemputan-harian.index')
            ->with('success', 'Data deleted successfully.');
    }
}
