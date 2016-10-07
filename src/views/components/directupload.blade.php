<form action="{{ route('upload') }}" method="post" enctype="multipart/form-data">
      <input type="file" name="files[]" id="filer_input" multiple="multiple">
      <input type="submit" value="Submit">
</form>


<input class="form-control inline image" type="text" name="{{ $component['name'] }}" id="{{ $component['name'] }}" data-limit="{{ $component['limit'] }}" value="{{ $component['data'] }}" readonly />



@section('styles')
	<link href="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/plugins/jquery-filer/css/jquery.filer.css') }}" rel="stylesheet">
	<link href="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/plugins/jquery-filer/css/themes/jquery.filer-dragdropbox-theme.css') }}" rel="stylesheet">
@stop

@section('scripts')
	<script type="text/javascript" src="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/plugins/jquery-filer/js/jquery.filer.min.js') }}"></script>

    <script type="text/javascript">
    	 $('#filer_input').filer({
         uploadFile: {
           url: '{{ route('upload') }}', //URL to which the request is sent {String}
           // data: null, //Data to be sent to the server {Object}
           type: 'POST', //The type of request {String}
           enctype: 'multipart/form-data', //Request enctype {String}
           // synchron: false //Upload synchron the files
           // beforeSend: null, //A pre-request callback function {Function}
           // success: null, //A function to be called if the request succeeds {Function}
           // error: null, //A function to be called if the request fails {Function}
           // statusCode: null, //An object of numeric HTTP codes {Object}
           // onProgress: null, //A function called while uploading file with progress percentage {Function}
           // onComplete: null //A function called when all files were uploaded {Function}
         }

       });
    </script>

@stop
