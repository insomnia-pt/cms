
<section class="panel panel-primary">
	<header class="panel-heading">
	Lista de Páginas
	</header>
	@if(count($parentPages))
	<ul class="list-group cd-accordion-menu animated">
		@foreach ($parentPages as $pageitem)
	       @include('cms::pages._treeview-menuitem', array('item' => $pageitem))
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

					( checkbox.prop('checked') ) ? Cookies.set("cms_pages_list", checkbox.attr('id')) : Cookies.remove('cms_pages_list') ;
				});
			});
		}

		var checkHistory = Cookies.get("cms_pages_list");
		var $checkedHistoryElm = $("#"+checkHistory);
		
		$checkedHistoryElm.click();
		while($checkedHistoryElm.parents('ul').parents('li.list-group-item').length){
			$checkedHistoryElm = $checkedHistoryElm.parents('ul').parents('li.list-group-item').children('input[type="checkbox"]');
			$checkedHistoryElm.click();
			Cookies.set("cms_pages_list", checkHistory);
		}
	</script>
@stop