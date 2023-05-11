<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Movies;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MovieController extends Controller
{
        /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.admin.movies.index', [
            'data' => Movies::latest()->paginate(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.admin.movies.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'price' => 'required',
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg'],
        ]);
        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'end_at' => $request->end_at,
            'start_at' => $request->start_at,
            'time_slots' => $request->time_slots ?? [],
            'price_in_cents' => $request->price ? $request->price * 100 : 0 ,
            'image' => Storage::put(Movies::$image_store_path, $request->file('image')),
        ];

        Movies::create($data);
        return redirect()->route('admin.movies.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
            // $ticket = Movies::query()->findOrFail($id);
            // return view('pages.admin.movies.edit', compact('ticket'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $ticket = Movies::query()->findOrFail($id);
        return view('pages.admin.movies.edit', ['movie' => $ticket]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $ticket = Movies::query()->findOrFail($id);
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'price' => 'required',
            'image' => ['image', 'mimes:jpeg,png,jpg'],
        ]);
        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'end_at' => $request->end_at,
            'start_at' => $request->start_at,
            'time_slots' => $request->time_slots ?? $ticket->time_slots,
            'price_in_cents' => $request->price ? $request->price * 100 : $ticket->price_in_cents,
        ];
        if($request->file('image'))
        {
            Storage::delete($ticket->image);
            $data['image'] = Storage::put(Movies::$image_store_path, $request->file('image'));
        }

        $ticket->update($data);
        return redirect()->route('admin.movies.index')->with('message', "Ticket updated!");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $ticket = Movies::query()->findOrFail($id);
        Storage::delete($ticket->image);
        $ticket->delete();
        return redirect()->route('admin.movies.index')->with('message', "Ticket deleted!");
    }
}
