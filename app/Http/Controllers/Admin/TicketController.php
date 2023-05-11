<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.admin.tickets.index', [
            'data' => Ticket::latest()->paginate(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.admin.tickets.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'price' => 'required',
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg'],
            'base_ticket_image' => ['required', 'image', 'mimes:jpeg,png,jpg'],
        ]);
        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'cant_buy_after_days' => $request->cant_buy_after_days,
            'off_days_list' => $request->off_days_list ? explode(',', $request->off_days_list) : [],
            'price_in_cents' => $request->price ? $request->price * 100 : 0 ,
            'discount_price_in_cents' => $request->discount_price ? $request->discount_price * 100 : 0 ,
            'image' => Storage::put(Ticket::$image_store_path, $request->file('image')),
            'base_ticket_image' => Storage::put(Ticket::$image_store_path, $request->file('base_ticket_image')),
        ];

        $ticket = Ticket::create($data);
        return redirect()->route('admin.tickets.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
            // $ticket = Ticket::query()->findOrFail($id);
            // return view('pages.admin.tickets.edit', compact('ticket'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $ticket = Ticket::query()->findOrFail($id);
        return view('pages.admin.tickets.edit', compact('ticket'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $ticket = Ticket::query()->findOrFail($id);
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'price' => 'required',
            'image' => ['image', 'mimes:jpeg,png,jpg'],
            'base_ticket_image' => ['image', 'mimes:jpeg,png,jpg'],
        ]);
        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'cant_buy_after_days' => $request->cant_buy_after_days,
            'off_days_list' => $request->off_days_list ? explode(',', $request->off_days_list) : [],
            'price_in_cents' => $request->price ? $request->price * 100 : $ticket->price_in_cents ,
            'discount_price_in_cents' => $request->discount_price ? $request->discount_price * 100 : $ticket->discount_price_in_cents,
        ];
        if($request->file('image'))
        {
            Storage::delete($ticket->image);
            $data['image'] = Storage::put(Ticket::$image_store_path, $request->file('image'));
        }
        if($request->file('base_ticket_image'))
        {
            Storage::delete($ticket->base_ticket_image);
            $data['base_ticket_image'] = Storage::put(Ticket::$image_store_path, $request->file('base_ticket_image'));
        }

        $ticket->update($data);
        return redirect()->route('admin.tickets.index')->with('message', "Ticket updated!");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $ticket = Ticket::query()->findOrFail($id);
        Storage::delete($ticket->image);
        Storage::delete($ticket->base_ticket_image);
        $ticket->delete();
        return redirect()->route('admin.tickets.index')->with('message', "Ticket deleted!");
    }
}
