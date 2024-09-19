<?php

namespace App\Http\Controllers;

use App\Models\Item; // Assuming you have a model named Item
use Illuminate\Http\Request;

class ItemController extends Controller
{
    // Display a listing of the items.
    public function index()
    {
        $items = Item::all();
        return view('items.index', compact('items'));
    }

    // Show the form for creating a new item.
    public function create()
    {
        return view('items.create');
    }

    // Store a newly created item in the database.
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'description' => 'nullable'
        ]);

        Item::create($request->all());
        return redirect()->route('items.index')->with('success', 'Item created successfully.');
    }

    // Show the form for editing an existing item.
    public function edit(Item $item)
    {
        return view('items.edit', compact('item'));
    }

    // Update the specified item in the database.
    public function update(Request $request, Item $item)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'description' => 'nullable'
        ]);

        $item->update($request->all());
        return redirect()->route('items.index')->with('success', 'Item updated successfully.');
    }

    // Remove the specified item from the database.
    public function destroy(Item $item)
    {
        $item->delete();
        return redirect()->route('items.index')->with('success', 'Item deleted successfully.');
    }
}
