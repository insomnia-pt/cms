
<input type="file" name="component-{{ str_replace(['[',']'], '_', $component['name']) }}" id="component-{{ str_replace(['[',']'], '_', $component['name']) }}" multiple="multiple" data-jfiler-extensions="{{ $component['extensions'] }}" data-jfiler-limit="{{ $component['limit'] }}" data-jfiler-files='{{ $component['data'] }}' data-folder="{{ $component['folder'] }}">


<input type="hidden" name="{{ $component['name'] }}" id="{{ str_replace(['[',']'], '_', $component['name']) }}" value='{{ $component['data'] }}' style="width: 100%" />

@section('substyles')
	<link href="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/plugins/jquery-filer/css/jquery.filer.css') }}" rel="stylesheet">
	<link href="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/plugins/jquery-filer/css/themes/jquery.filer-dragdropbox-theme.css') }}" rel="stylesheet">
@stop

@section('subscripts')
	<script type="text/javascript" src="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/plugins/jquery-filer/js/jquery.filer.js') }}"></script>
	<script type="text/javascript" src="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/plugins/jquery-filer/js/template-preview.js') }}"></script>

    <script type="text/javascript">

			var images_{{ str_replace(['[',']'], '_', $component['name']) }} = $("#{{ str_replace(['[',']'], '_', $component['name']) }}").val()?JSON.parse($("#{{ str_replace(['[',']'], '_', $component['name']) }}").val()):[];
			var componentVal_{{ str_replace(['[',']'], '_', $component['name']) }} = $.merge(images_{{ str_replace(['[',']'], '_', $component['name']) }}, []);

    	$('#component-{{ str_replace(['[',']'], '_', $component['name']) }}').filer({
    	    templates: filerTemplatePreview,
            canvasImage: true,
            synchron: true,
            showThumbs: true,
            dragDrop: {
                dragEnter: null,
                dragLeave: null,
                drop: null,
                dragContainer: null
            },
            uploadFile: {
                url: '{{ route('upload') }}',
                data: { field:'component-{{ str_replace(['[',']'], '_', $component['name']) }}', folder: $("#component-{{ str_replace(['[',']'], '_', $component['name']) }}").data('folder') },
                type: 'POST',
                enctype: 'multipart/form-data',
                //synchron: true, //Upload synchron the files
                beforeSend: function(){},
                success: function(data, el){
                    var parent = el.find(".jFiler-jProgressBar").parent();
                    el.find(".jFiler-jProgressBar").fadeOut("slow", function(){
                        $("<div class=\"jFiler-item-others text-success\"><i class=\"icon-jfi-check-circle\"></i> </div>").hide().appendTo(parent).fadeIn("slow");
                    });

                    componentVal_{{ str_replace(['[',']'], '_', $component['name']) }}.push({
                        name: data.metas[0].old_name,
                        size: data.metas[0].size,
                        type: (data.metas[0].type).join('/'),
                        file: '/'+data.metas[0].file
                    });

                    var x = componentVal_{{ str_replace(['[',']'], '_', $component['name']) }};
                    $("#{{ str_replace(['[',']'], '_', $component['name']) }}").val(JSON.stringify(x));
                },

                error: function(el){
                    var parent = el.find(".jFiler-jProgressBar").parent();
                    el.find(".jFiler-jProgressBar").fadeOut("slow", function(){
                        $("<div class=\"jFiler-item-others text-error\"><i class=\"icon-jfi-minus-circle\"></i> Erro</div>").hide().appendTo(parent).fadeIn("slow");
                    });
                },
               // statusCode: null, //An object of numeric HTTP codes {Object}
               // onProgress: null, //A function called while uploading file with progress percentage {Function}
              // onComplete: function(){	 }
            },
             onRemove: function(el, item, index){

                 componentVal_{{ str_replace(['[',']'], '_', $component['name']) }}.splice(index,1);

                 var x = componentVal_{{ str_replace(['[',']'], '_', $component['name']) }};
                 $("#{{ str_replace(['[',']'], '_', $component['name']) }}").val(JSON.stringify(x));

             },
			 captions: {
             button: "Escolher Ficheiros",
             feedback: "Escolha ou arraste o ficheiro para esta área",
             feedback2: "ficheiros escolhidos",
             drop: "Drop file here to Upload",
             removeConfirmation: "Confirma remover este ficheiro?",
             errors: {
                 filesLimit: "Ultrapassou o número de ficheiros permitidos.",
                 filesType: "O tipo de ficheiro não é válido.",
                 filesSize: "O tamanho do ficheiro ultrapassa o limite permitido.",
                 filesSizeAll: "O tamanho dos ficheiros ultrapassa o limite permitido.",
                 folderUpload: "Não é possível fazer upload de pastas."
             }
         }

       });
    </script>

@append
