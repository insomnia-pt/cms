<?php
$parentItems = $dsItems->filter(function($item) {
    return $item->id_parent == 0;
})->values();
?>
<section class="panel panel-primary">
	<header class="panel-heading">
	Lista de Registos
  @if(@!$datasource->options()->orderby)
  <button type="button" class="btn btn-xs btn-default pull-right" data-status-on="Cancelar" data-status-off="Reordenar" id="bt-listorder" style="margin-top:-2px"><span>Reordenar</span> <i class="fa fa-sort-amount-asc"></i></button>
  <form class="pull-right" action="{{ route('cms/ds/order', $datasource->id) }}" id="form-savelistorder" method="post" style="margin-top:-2px;margin-right:5px;display:none;">
    <input type="hidden" name="ds-orderlist-listview" id="ds-orderlist-listview" value="">
    <button type="submit" class="btn btn-xs btn-danger">Guardar Alterações</button>
  </form>
  @endif
	</header>
	@if(count($parentItems))
    <div id="dslist" class="dd dd-nodrag">
    	<ol class="dd-list">
    		@foreach ($parentItems as $parentitem)
    	       @include('cms::ds._treeview-menuitem', array('item' => $parentitem))
    	    @endforeach
    	</ol>

	@else
	<ul class="list-group cd-accordion-menu animated">
		<li class="list-group-item">Não existem dados</li>
	</ul>
	@endif
</section>

@section('subscripts')
	<script type="text/javascript">

  var reorderStatus = 0;
  $("#bt-listorder").click(function(){
    console.log(JSON.stringify($('#dslist').nestable('serialize')));
    $("#ds-orderlist-listview").val(JSON.stringify($('#dslist').nestable('serialize')));
    if(reorderStatus){
      $('#dslist').addClass('dd-nodrag');
      $(this).find('span').text($(this).data('status-off'));
      $('#form-savelistorder').fadeOut(100);
      reorderStatus=0;
    } else {
      $('#dslist').removeClass('dd-nodrag');
      $(this).find('span').text($(this).data('status-on'));
      $('#form-savelistorder').fadeIn(100);
      reorderStatus=1;
    }
    $(this).toggleClass('btn-default btn-white');
  });

  $('#dslist').nestable({
    @if(@$datasource->options()->subitems_level)maxDepth: {{ $datasource->options()->subitems_level }},@endif 
    callback: function(l,e){
      $("#ds-orderlist-listview").val(JSON.stringify($('#dslist').nestable('serialize')));
    }
  });

	</script>
@stop
