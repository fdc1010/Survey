<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ URL::to('css/app.css') }}">

    <title>Laravel Excel Import csv and XLS file in Database</title>
    <link rel="stylesheet" href="{{ asset('css/ladda-themeless.min.css') }}">
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
    <!-- Styles -->
    <style>
    html, body {
        background-color: #fff;
        color: #636b6f;
        font-family: 'Raleway', sans-serif;
        font-weight: 100;
        height: 100vh;
        margin: 0;
        padding: 5%
    }
</style>
</head>
<body>
    <div class="container">
        <h2 class="text-center">
            Laravel Excel/CSV Import
        </h2>

        @if ( Session::has('success') )
        <div class="alert alert-success alert-dismissible" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
            <span class="sr-only">Close</span>
        </button>
        <strong>{{ Session::get('success') }}</strong>
    </div>
    @endif
    <div class="alert alert-danger alert-dismissible" role="alert" id="uploaderror">
    </div>
    @if ( Session::has('error') )
    <div class="alert alert-danger alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
            <span class="sr-only">Close</span>
        </button>
        <strong>{{ Session::get('error') }}</strong>
    </div>
    @endif

    @if (count($errors) > 0)
    <div class="alert alert-danger">
      <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
      <div>
        @foreach ($errors->all() as $error)
        <p>{{ $error }}</p>
        @endforeach
    </div>
</div>
@endif
<h1>Import Excel Masterlist of Voters</h1>
<form action="{{ route('importvoters2') }}" method="POST" enctype="multipart/form-data" id="formvoters">
    {{ csrf_field() }}
    Choose your xls/csv File : <input type="file" name="filevoters" id="filevoters" class="form-control">

    <button class="ladda-button btn btn-primary btn-lg" data-style="expand-right" id="btnsubmit" style="margin-top: 3%"><span class="ladda-label">Submit</span></button>
    <input type="hidden" name="index" id="index" value="0">
</form>
</div>
<script src="{{ asset('js/jquery-1.12.4.js') }}"></script>
<script src="{{ asset('js/jquery.validate.js') }}"></script>
<script src="{{ asset('js/additional-methods.js') }}"></script>
<script src="{{ asset('js/spin.min.js') }}"></script>
<script src="{{ asset('js/ladda.min.js') }}"></script>
<script>
$(function() {
  var l = Ladda.create( document.querySelector( '#btnsubmit' ) );
  $("#formvoters").validate({
    rules: {
      filevoters: {
        required: true,
        extension: "xls|xlsx|csv"
      }
    },
    messages: {
        filevoters: "File must be XLS, XLSX or CSV"
    },
    submitHandler: function(form) {
        l.start();
        var formData = new FormData();
        formData.append('index', $('#index').val());
        formData.append('file', $('#filevoters')[0].files[0]);
        $.ajax({
            url: form.action,
            type: form.method,
            data: new FormData(form),
            processData: false,  // tell jQuery not to process the data
            contentType: false,  // tell jQuery not to set contentType
            success: function(response) {
                var msg = response;
                console.log(msg);
                $('#index').val(parseInt(msg.index)-1);
                $('#uploaderror').html(msg.messages);
                $('#uploaderror').show('slow');
                l.stop();
            }, error: function (xhr, ajaxOptions, thrownError) {
                var msg = xhr.responseJSON;
                console.log(msg);
                $.each(msg.errors,function(key,value){
                      $('#uploaderror').html(value[0]);
                });
                $('#uploaderror').show('slow');
                l.stop();
    			  }
        });
    }
  });
  $('#uploaderror').hide();
  $('#index').val(0);
});
</script>
</body>
</html>
