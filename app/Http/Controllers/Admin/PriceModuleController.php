<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PriceModule;
use Illuminate\Http\Request;

class PriceModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $modules = PriceModule::orderBy('id', 'asc')->get();
        return view('admin.price-modules.index', compact('modules'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $module = new PriceModule(); // For binding the form
        return view('admin.price-modules.form', compact('module'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'key' => 'required|string|unique:price_modules',
            'label_de' => 'required|string',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'type' => 'required|in:boolean,quantity,select',
            'category' => 'nullable|string',
            'options' => 'nullable|array',
            'options.*.label' => 'required_with:options|string',
            'options.*.price' => 'required_with:options|numeric',
        ]);

        $data['is_active'] = $request->has('is_active'); // checkbox

        PriceModule::create($data);

        return redirect()->route('admin.price-modules.index')->with('success', 'Modul erfolgreich erstellt.');
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
    public function edit(PriceModule $priceModule)
    {
        $module = $priceModule;
        return view('admin.price-modules.form', compact('module'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PriceModule $priceModule)
    {
        $data = $request->validate([
            'key' => 'required|string|unique:price_modules,key,' . $priceModule->id,
            'label_de' => 'required|string',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'type' => 'required|in:boolean,quantity,select',
            'category' => 'nullable|string',
            'options' => 'nullable|array',
            'options.*.label' => 'required_with:options|string',
            'options.*.price' => 'required_with:options|numeric',
        ]);

        $data['is_active'] = $request->has('is_active'); // checkbox

        $priceModule->update($data);

        return redirect()->route('admin.price-modules.index')->with('success', 'Modul erfolgreich aktualisiert.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PriceModule $priceModule)
    {
        try {
            $priceModule->delete();
            return redirect()->route('admin.price-modules.index')->with('success', 'Modul gelöscht.');
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == "23000") {
                return redirect()->route('admin.price-modules.index')
                    ->with('error', 'Dieses Modul kann nicht gelöscht werden, da es bereits in früheren Anfragen (Inquiries) verwendet wurde. Bitte ändern Sie stattdessen den Status auf "Inaktiv".');
            }
            throw $e;
        }
    }
}
