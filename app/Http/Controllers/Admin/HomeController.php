<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MovieTicket;
use App\Models\Setting;
use App\Models\UserTicket;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    public function reports($category = 'entry')
    {
        $request = request();
        $tickets = [];
        $from = now()->subWeek();
        $to = now()->addHour();
        if($request->from)
        {
            $from = Carbon::parse($request->from);
        }
        if($request->to)
        {
            $to = Carbon::parse($request->to);
        }
        if($category === 'entry')
        {
            $tickets = UserTicket::latest()
                ->where('created_at', '<', $to)
                ->where('created_at', '>', $from)
                ->paginate();
        }else 
        {
            $tickets = MovieTicket::latest()
                ->where('created_at', '<', $to)
                ->where('created_at', '>', $from)
                ->paginate();
        }
        return view('pages.admin.reports', [
            'tickets' => $tickets,
            'category' => $category === 'entry' ? $category : 'movie',
            'from' => $from,
            'to' => $to,
        ]);
        //{{ $tickets->appends($_GET)->links() }}
    }
    public function reports_update_status(Request $request)
    {
        $used = $request->val == 1;
        if($request->category === 'entry')
        {
            $ticket = UserTicket::query()->findOrFail($request->id);
        }else 
        {
            $ticket = MovieTicket::query()->findOrFail($request->id);
        }
        $ticket->update(['used_at' => $used ? now() : null]);
        return redirect()->back()->with('message', "Ticket update to ". ($used ? ' used' : ' not used'));    
    }
    public function reports_ticket_delete($id)
    {
        $ti = UserTicket::query()->findOrFail($id);
        $ti->delete();
        return redirect()->back()->with('message', "Ticket deleted ");  
    }
    public function reports_movie_delete($id)
    {
        $ti = MovieTicket::query()->findOrFail($id);
        $ti->delete();
        return redirect()->back()->with('message', "Ticket deleted ");          
    }
    function settings()  
    {
        return view('pages.admin.settings');    
    }
    function settings_store(Request $request) 
    {
        // $request->dd();
        if($request->home_section_text_1)
        {
            $file = $request->home_section_text_1;
            $check = Setting::query()->where('key', 'home_section_text_1')->first();
            if($check) 
            {
                $check->update([
                    'value' => $request->home_section_text_1,
                ]);
            }else {
                Setting::query()->create([
                    'value' => $request->home_section_text_1,
                    'key' => 'home_section_text_1'
                ]);
            }
        }
        if($request->home_section_text_2)
        {
            $file = $request->home_section_text_2;
            $check = Setting::query()->where('key', 'home_section_text_2')->first();
            if($check) 
            {
                $check->update([
                    'value' => $request->home_section_text_2,
                ]);
            }else {
                Setting::query()->create([
                    'value' => $request->home_section_text_2,
                    'key' => 'home_section_text_2'
                ]);
            }
        }

        if($request->file('home_slider_1'))
        {
            $file = $request->file('home_slider_1');
            $new_file = Storage::put(Setting::$image_store_path, $file);
            $check = Setting::query()->where('key', 'home_slider_1')->first();
            if($check) 
            {
                Storage::delete($check->value);
                $check->update([
                    'value' => $new_file,
                ]);
            }else {
                Setting::query()->create([
                    'value' => $new_file,
                    'key' => 'home_slider_1'
                ]);
            }
        }
        if($request->file('home_slider_2'))
        {
            $file = $request->file('home_slider_2');
            $new_file = Storage::put(Setting::$image_store_path, $file);
            $check = Setting::query()->where('key', 'home_slider_2')->first();
            if($check) 
            {
                Storage::delete($check->value);
                $check->update([
                    'value' => $new_file,
                ]);
            }else {
                Setting::query()->create([
                    'value' => $new_file,
                    'key' => 'home_slider_2'
                ]);
            }
        }
        if($request->file('home_slider_3'))
        {
            $file = $request->file('home_slider_3');
            $new_file = Storage::put(Setting::$image_store_path, $file);
            $check = Setting::query()->where('key', 'home_slider_3')->first();
            if($check) 
            {
                Storage::delete($check->value);
                $check->update([
                    'value' => $new_file,
                ]);
            }else {
                Setting::query()->create([
                    'value' => $new_file,
                    'key' => 'home_slider_3'
                ]);
            }
        }
        return back()->with("status", "Data updated!");
    }
}
