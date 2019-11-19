@extends('layout')

@section('title')
Profile
@endsection
@section('scripts')

    <script>
      $('#toggleOptin').click(function () {
        let icon  = $(this).find('i.toggle-icon'),
            currentlyOn = icon.hasClass('fa-toggle-on'),
            spinner = $(this).find('i.spinner-icon')
        spinner.show()
        $.ajax({
          type: 'POST',
          url : "{{ secure_url("/dashboard/controllers/profile") }}"
        }).success(function (result) {
          spinner.hide()
          if (result === '1') {
            //Success
            icon.attr('class', 'toggle-icon fa fa-toggle-' + (currentlyOn ? 'off' : 'on') +
              ' text-' + (currentlyOn ? 'danger' : 'success'))
          }
          else {
            bootbox.alert('<div class=\'alert alert-danger\'><i class=\'fa fa-warning\'></i> <strong>Error!</strong> Unable to toggle email opt-in setting.')
          }
        })
          .error(function (result) {
            spinner.hide()
            bootbox.alert('<div class=\'alert alert-danger\'><i class=\'fa fa-warning\'></i> <strong>Error!</strong> Unable to toggle email opt-in setting.')
          })
      })
    </script>

@endsection
@section('content')

<div class="container">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    My Profile
                </h3>
            </div>
        <div class="panel-body">
            <form class="form-horizontal">
                <div class="form-group">
                     <label class="col-sm-2 control-label">Name</label>

                    <div class="col-sm-10">
                        <p class="form-control-static">{{Auth::user()->fname}} {{Auth::user()->lname}}</p>
                    </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Email</label>
                        <div class="col-sm-10">
                            <p class="form-control-static">{{Auth::user()->email}}</p>
                            <span id="helpBlock" class="help-block">Click <a
                                 href="http://cert.vatsim.net/vatsimnet/newmail.php">here</a> to change.</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Rating</label>
                            <div class="col-sm-10">
                                <p class="form-control-static">{{Auth::user()->RatingLong}}<br></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Receive Broadcast Emails</label>
                            <div class="col-sm-10">
                                <span id="toggleOptin" style="font-size:1.8em;">
                                
                                    <i class="toggle-icon fa fa-toggle-{{ Auth::user()->opt ?"on text-success" : "off text-danger"}} "></i>
                                    <i class="spinner-icon fa fa-spinner fa-spin" style="display:none;"></i>
                            </span>
                            <p class="help-block">To receive emails from the ZTL's mass emailing system, you must
                                opt-in by
                                clicking on the toggle switch above. <strong>This setting does not affect
                                account-related emails like training tickets/sessions and exam results/assignments.</strong>
                            </p>
                        </div>
                    </div>
                   
           
                
        
                </form>
            </div>
</div>




<div class="container">
    <div class="row">
        <div class="col-sm-6">
            <center><h4>My Feedback:</h4></center>
            <div class="table">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="col"><center>Position</center></th>
                            <th scope="col"><center>Result</center></th>
                            <th scope="col"><center>Comments</center></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($feedback->count() > 0)
                            @foreach($feedback as $f)
                                <tr>
                                    <td><center><a data-toggle="tooltip" title="View Details" href="/dashboard/controllers/profile/feedback-details/{{ $f->id }}">{{ $f->position }}</a></center></td>
                                    <td><center>{{ $f->service_level_text }}</center></td>
                                    <td><center>{{ str_limit($f->comments, 50, '...') }}</center></td>
                                </tr>
                            @endforeach
                    </tbody>
                </table>
                        @else
                    </tbody>
                </table>
                            <p>No feedback found.</p>
                        @endif
            </div>
        </div>
        <div class="col-sm-6">
            <center><h4>My Training Tickets:</h4></center>
            <div class="table">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="col"><center>Date</center></th>
                            <th scope="col"><center>Trainer</center></th>
                            <th scope="col"><center>Position</center></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($tickets))
                            @foreach($tickets as $t)
                                <tr>
                                    <td><center><a href="/dashboard/controllers/ticket/{{ $t->id }}">{{ $t->date }}</a></center></td>
                                    <td><center>{{ $t->trainer_name }}</center></td>
                                    <td><center>{{ $t->position_name }}</center></td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
                @if(!isset($tickets))
                    <p>No training tickets found.</p>
                @endif
            </div>
            @if(isset($tickets))
                {!! $tickets->links() !!}
            @endif
        </div>
    </div>
    <hr>
   
</div>

@stop
