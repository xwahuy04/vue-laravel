<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Item;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;


class ItemController extends Controller
{

    public function index()
    {
        $items = Item::all();
        return response()->json([
            'success' => true,
            'message' => 'All Item',
            'data' => [
                'items' => $items
            ]
        ], 200);

    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:100',
            'price' => 'required|integer',
            'image_file' => 'nullable|mimes:png,jpg'
        ]);

        if($request->file('image_file'))
        {
            $file = $request->file('image_file');
            $filename = $file->getClientOriginalName();
            $newName = Carbon::now()->timestamp.'_'.$filename;

            Storage::disk('public')->putFileAs('items', $file, $newName);
            $request['image'] = $newName;
        }

        $item = Item::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Create Item successful',
            'data' => [
                'item' => [
                    'id' => $item->id,
                    'name' => $item->name,
                    'price' => $item->price,
                    'image_file' => $item->image
                ]
            ]
        ], 200);

    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:100',
            'price' => 'required|integer',
            'image_file' => 'nullable|mimes:png,jpg,jpeg'
        ]);

        $item = Item::findOrFail($id);

        // Cek jika ada file baru
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $filename = $file->getClientOriginalName();
            $newName = Carbon::now()->timestamp . '_' . $filename;

            // Hapus file lama jika ada
            if ($item->image && Storage::disk('public')->exists('items/' . $item->image)) {
                Storage::disk('public')->delete('items/' . $item->image);
            }

            // Simpan file baru ke storage
            Storage::disk('public')->putFileAs('items', $file, $newName);

            // Simpan nama file baru ke model
            $item->image = $newName;
        }

        // Update field lain
        $item->name = $request->name;
        $item->price = $request->price;

        // Simpan ke database
        $item->save();

        return response()->json([
            'success' => true,
            'message' => 'Update Item successful',
            'data' => [
                'item' => [
                    'id' => $item->id,
                    'name' => $item->name,
                    'price' => $item->price,
                    'image_file' => $item->image 
                        ? asset('storage/items/' . $item->image)
                        : null
                ]
            ]
        ], 200);
    }


}
