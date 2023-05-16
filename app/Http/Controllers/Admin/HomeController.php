<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MovieTicket;
use App\Models\UserTicket;
use Carbon\Carbon;
use Illuminate\Http\Request;

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
}
