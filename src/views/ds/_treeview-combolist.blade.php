<?php
	$subItems = $dsItems->filter(function($subitem) use ($item) {
	    return $subitem->id_parent == $item->id;
	})->values();
?>

@if($item->id != @$editing)
<li data-id="{{ $item->id }}" data-text="{{ @$relation?$item->{$relation->config()->fields[0]}:$item->{$datasource->config()[0]->name} }}" class="{{ @$selected==$item->id?'li_selected':'' }}">
    @if(count($subItems)>0)
		{{ @$relation?$item->{$relation->config()->fields[0]}:$item->{$datasource->config()[0]->name} }} 
        <ul>
        @foreach ($subItems as $subitem)
            @include('ocms::ds._treeview-combolist', array('item' => $subitem, 'relation'=>@$relation, 'selected'=>@$selected))
        @endforeach
    	</ul>
    @else
    	{{ @$relation?$item->{$relation->config()->fields[0]}:$item->{$datasource->config()[0]->name} }}
    @endif
</li>
@endif