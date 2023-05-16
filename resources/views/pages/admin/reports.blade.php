@extends('layouts.app')

@section('content')
<style>
    .date {
        width: 140px
    }
</style>
    <div class="container mt-2">
        <h3 class="mb-4">
            Reports: 
        </h3>
        <ul class="nav nav-tabs" id="ex1" role="tablist">
            <li class="nav-item">
                <a
                    href="{{ route('admin.reports') }}"
                    class="nav-link @if ($category === 'entry') active @endif"
                >
                    Entry 
                </a>
            </li>
            <li class="nav-item ">
                <a
                    href="{{ route('admin.reports', 'movie') }}"
                    class="nav-link @if ($category === 'movie') active @endif"
                >
                    Movie  
                </a>
            </li>
        </ul>
        <div class="mt-4 mb-3">
            <form action="" method="get">
                Select Date Range: 
                <input type="text" class="date" name="from" value="{{ $from->format('d-m-Y') }}">
                <span class="badge text-dark">to</span>
                <input type="text" class="date" name="to" value="{{ $to->format('d-m-Y') }}">
                <br>
                <button class="btn btn-sm btn-success mt-2">
                    Apply date
                </button>
            </form>
        </div>
        <hr>
        <table class="table">
            <thead>
                <tr>
                    <th>
                        ID 
                    </th>
                    <th>
                        Name 
                    </th>
                    <th>
                        Phone 
                    </th>
                    @if ($category === 'entry')
                        <th>
                            Date   
                        </th>
                    @else 
                        <th>
                            Date & Time  
                        </th>
                        <th>
                            Seat No.  
                        </th>
                    @endif
                    <th>
                        Used
                    </th>
                    <th>
                        Qty 
                    </th>
                    <th>
                        Price 
                    </th>
                    <th>
                        Action
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse ($tickets as $item)
                    <tr>
                        <td>
                            {{$item->id}}
                        </td>
                        <td>
                            {{$item->user->name}}
                        </td>
                        <td>
                            {{$item->user->phone}}
                        </td>
                        @if ($category === 'entry')
                            <th>
                                {{ \Carbon\Carbon::parse($item->date)->format('d F, Y') }}
                            </th>
                        @else 
                            <th>
                                {{ \Carbon\Carbon::parse($item->date)->format('d F, Y - h:i A') }}
                            </th>
                            <th>
                                {{$item->seat_no}}
                            </th>
                        @endif
                        <th>
                            <select onchange="changeUsedStatus({{$item->id}}, '{{ $category }}', this)" style="width: 100px" class="form-control">
                                <option @if($item->used_at) selected @endif value="yes">Yes</option>
                                <option @if(!$item->used_at) selected @endif value="no">No</option>
                            </select>
                        </th>
                        <th>
                            @if ($category === 'entry')
                                {{ $item->quantity }}
                            @else 
                                1 
                            @endif
                        </th>
                        <th>
                            {{$item->price}} Tk
                        </th>
                        <th>
                            <button class="btn btn-sm btn-danger" 
                                onclick="if(confirm('Do you really want to delete this ticket?')){document.querySelector('#dele{{$item->id}}').submit()}"
                            >
                                Delete 
                            </button>
                            <form 
                                id="dele{{$item->id}}"
                                action="{{ 
                                    ($category === 'entry')
                                        ? route('admin.reports.delete.ticket', $item->id) 
                                        : route('admin.reports.delete.movie', $item->id) 
                                }}" method="POST">
                                @method('DELETE')
                                @csrf
                            </form>
                        </th>
                    </tr>
                @empty
                    <tr>
                        <th>
                            <i>
                                Nothing Found!
                            </i>
                        </th>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-2">
            {{ $tickets->appends($_GET)->links() }}
        </div>
    </div>
    <script>
        function changeUsedStatus(id, cate, select) {
            // alert(select.value)
            location = "{{ route('admin.reports.update-status') }}" + "?id="+id+"&category=" + cate + "&val=" + (select.value == 'yes' ? 1 : 0)
        }
        contentLoaded(() => {
            $('.date').datepicker({
                format: "dd-mm-yyyy",
            })
        })
    </script>
@endsection 
