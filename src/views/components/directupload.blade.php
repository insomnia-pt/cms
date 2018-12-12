
<input type="file" name="component-{{ str_replace(['[',']'], '_', $component['name']) }}" id="component-{{ str_replace(['[',']'], '_', $component['name']) }}" multiple="multiple" data-jfiler-extensions="{{ $component['extensions'] }}" data-jfiler-limit="{{ $component['limit'] }}" data-jfiler-files='{{ $component['data'] }}' data-folder="{{ $component['folder'] }}" data-compress="{{ $component['compress'] }}">


<input type="hidden" name="{{ $component['name'] }}" id="{{ str_replace(['[',']'], '_', $component['name']) }}" value='{{ $component['data'] }}' style="width: 100%" />

@section('substyles')
	<link href="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/plugins/jquery-filer/css/jquery.filer.css') }}" rel="stylesheet">
	<link href="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/plugins/jquery-filer/css/themes/jquery.filer-dragdropbox-theme.css') }}" rel="stylesheet">
@stop

@section('subscripts')
	<script type="text/javascript" src="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/plugins/jquery-filer/js/jquery.filer.js') }}"></script>
	<script type="text/javascript" src="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/plugins/jquery-filer/js/template-preview.js') }}"></script>
	<script type="text/javascript" src="{{ Helpers::asset(Config::get('cms::config.assets_path').'/assets/js/jquery-sortable-min.js') }}"></script>

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
                data: { field:'component-{{ str_replace(['[',']'], '_', $component['name']) }}', folder: $("#component-{{ str_replace(['[',']'], '_', $component['name']) }}").data('folder'), compress: $("#component-{{ str_replace(['[',']'], '_', $component['name']) }}").data('compress') },
                type: 'POST',
                enctype: 'multipart/form-data',
                //synchron: true, //Upload synchron the files
                beforeSend: function(){},
                success: function(data, el){
                    var parent = el.find(".jFiler-jProgressBar").parent();
                    el.find(".jFiler-jProgressBar").fadeOut("slow", function(){
                        $("<div class=\"jFiler-item-others text-success\"><i class=\"icon-jfi-check-circle\"></i> </div>").hide().appendTo(parent).fadeIn("slow");
                        var elemFileInfo = el.find('.jFiler-item-info');
                        var elemFileLink = $("<a></a>").attr('href', '/'+data.metas[0].file).attr('target', '_blank').html(el.find('.jFiler-item-info > div').html());
                        elemFileInfo.html(elemFileLink);
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



       $(document).ready(function(){

            var area = $('#component-{{ str_replace(['[',']'], '_', $component['name']) }}').next().next('.jFiler-items').find('.jFiler-items-list');

            area.sortable({
                pullPlaceholder: true,
                placeholder: '<i class="fa fa-caret-right text-danger"></i>',
                handle: '.fa-arrows-alt',
                itemSelector: '.jFiler-item',
                vertical: false,
                onDrop: function  ($item, container, _super) {
                    var $clonedItem = $('<li/>').css({height: 0});
                    $item.before($clonedItem);
                    $clonedItem.animate({'height': $item.height()});

                    area.css({"border":"none", "padding":"0"});

                    $item.animate($clonedItem.position(), 10, function  () {
                        $clonedItem.detach();
                        _super($item, container);
                        
                        var listOrder = [];
                        area.find('li.jFiler-item').each(function(index) {
                            var elem = $(this);
                            $.each(componentVal_{{ str_replace(['[',']'], '_', $component['name']) }}, function(index, item){
                                if(elem.find('.jFiler-item-info a').attr('href') == item.file) listOrder.push(item)
                            })                            
                        });

                        listOrder.reverse();
                        $("#{{ str_replace(['[',']'], '_', $component['name']) }}").val(JSON.stringify(listOrder));
                    });
                },

                onDragStart: function ($item, container, _super) {
                    var offset = $item.offset(),
                        pointer = container.rootGroup.pointer;

                    adjustment = {
                    left: pointer.left - offset.left,
                    top: pointer.top - offset.top
                    };

                    area.css({"border":"1px solid #eee", "padding":"5px"});

                    _super($item, container);
                },
                onDrag: function ($item, position) {
                    $item.css({
                    left: position.left - adjustment.left,
                    top: position.top - adjustment.top
                    });
                }
            });

        });
    </script>

@append
