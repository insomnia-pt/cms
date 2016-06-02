<?php
$parentItems = $dsItems->filter(function($item) {
    return $item->id_parent == 0;
})->values();
?>
<section class="panel panel-primary">
	<header class="panel-heading">
	Lista de Registos
	</header>
	@if(count($parentItems))
	<ul class="list-group cd-accordion-menu animated">
		@foreach ($parentItems as $parentitem)
	       @include('ocms::ds._treeview-menuitem', array('item' => $parentitem))
	    @endforeach
	</ul>
	@else
	<ul class="list-group cd-accordion-menu animated">
		<li class="list-group-item">Não existem dados</li>
	</ul>
	@endif
</section>

@section('subscripts')
	<script type="text/javascript">
    	var accordionsMenu = $('.cd-accordion-menu');
		if(accordionsMenu.length > 0 ) {
			accordionsMenu.each(function(){
				var accordion = $(this);
				accordion.on('change', 'input[type="checkbox"]', function(){
					var checkbox = $(this);
					( checkbox.prop('checked') ) ? checkbox.siblings('ul').attr('style', 'display:none;').slideDown(300).parent().addClass('opened') : checkbox.siblings('ul').attr('style', 'display:block;').slideUp(300).parent().removeClass('opened');
				});
			});
		}
	</script>
@stop