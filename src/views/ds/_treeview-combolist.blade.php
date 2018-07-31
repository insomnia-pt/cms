<?php
	$subItems = $dsItems->filter(function($subitem) use ($item) {
	    return $subitem->id_parent == $item->id;
    })->values();
    
    if(@$relation) {
        $field = null;
        foreach($relationTableModel->config() as $struct) {
            if ($relation->config()->fields[0] == $struct->name) {
                $field = $struct;
                break;
            }
        }
    }
?>

@if($item->id != @$editing)
<li data-id="{{ $item->id }}" data-text="@if(@$relation){{ @$field->multilang ? @json_decode($item->{$relation->config()->fields[0]})->{$settings->language} : $item->{$relation->config()->fields[0]} }} @else {{ $datasource->config()[0]->multilang ? @json_decode($item->{$datasource->config()[0]->name})->{$settings->language} : $item->{$datasource->config()[0]->name} }} @endif" class="{{ @$selected==$item->id?'li_selected':'' }}">
    @if(count($subItems)>0)
        @if(@$relation) 
            {{ @$field->multilang ? @json_decode($item->{$relation->config()->fields[0]})->{$settings->language} : $item->{$relation->config()->fields[0]} }}
        @else
            {{ $datasource->config()[0]->multilang ? @json_decode($item->{$datasource->config()[0]->name})->{$settings->language} : $item->{$datasource->config()[0]->name} }}
        @endif
        <ul>
        @foreach ($subItems as $subitem)
            @include('cms::ds._treeview-combolist', array('item' => $subitem, 'relation'=>@$relation, 'selected'=>@$selected))
        @endforeach
    	</ul>
    @else
        @if(@$relation) 
            {{ @$field->multilang ? @json_decode($item->{$relation->config()->fields[0]})->{$settings->language} : $item->{$relation->config()->fields[0]} }}
        @else
            {{ $datasource->config()[0]->multilang ? @json_decode($item->{$datasource->config()[0]->name})->{$settings->language} : $item->{$datasource->config()[0]->name} }}
        @endif
    @endif
</li>
@endif