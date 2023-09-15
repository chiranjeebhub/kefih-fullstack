@extends('fronted.layouts.app_new')
@section('content')

<section class="wrap inr-wrap-tp cartwrap">
    <div class="container-fluid">
        <div class="title">
        <h4>Notification</h4> 
        </div>
       <div class="row">
           <div class="col-md-12 col-12 col-sm-12">
               <div class="notificationMain mt-2">
                 @foreach($notifications as $row)
                    <div class="notifInner">
                        <div class="notifiLeft">
                            <h2>{{$row->title}}</h2>
                            <p>{{$row->body}}</p>
                        </div>
                        <div class="notiTimeBox">
                            <div><i class="bi bi-calendar-event"></i> {{date('d-m-Y',strtotime($row->notfication_data))}} <span>{{date('h:i a',strtotime($row->notfication_data))}}</span></div>
                        </div>
                    </div>
                @endforeach
                 
                   
               </div>
           </div>
        </div>
    </div>
    <br><br>
    {{$notifications->links()}}
</section>
@endsection