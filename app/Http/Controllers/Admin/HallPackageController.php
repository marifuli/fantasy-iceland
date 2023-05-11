<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HallPackage;
use Illuminate\Http\Request;

class HallPackageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.admin.hall-packages.index', [
            'data' => HallPackage::query()->latest()->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.admin.hall-packages.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'seats' => 'required',
            'price' => 'required',   
        ]);
        HallPackage::create([
            'name' => $request->name,
            'seats' => $request->seats,
            'price_in_cents' => $request->price * 100,
        ]);
        return redirect()->route('admin.hall-packages.index')->with('message', "Package added");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view('pages.admin.hall-packages.edit', [
            'package' => HallPackage::query()->findOrFail($id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required',
            'seats' => 'required',
            'price' => 'required',   
        ]);
        HallPackage::query()->findOrFail($id)->update([
            'name' => $request->name,
            'seats' => $request->seats,
            'price_in_cents' => $request->price * 100,
        ]);
        return redirect()->route('admin.hall-packages.index')->with('message', "Package updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        HallPackage::query()->findOrFail($id)->delete();
        return redirect()->route('admin.hall-packages.index')->with('message', "Package deleted");
    }
}
