<?php
    $subItems = $dsItems->filter(function($subitem) use ($item) {
        return $subitem->id_parent == $item->id;
    })->values();
?>

<li class="list-group-item  @if (count($subItems)>0) has-children @endif">
        <div class="actions pull-right">
            @foreach($datasource->relations as $relation)
            <?php 
                $relationTable = Insomnia\Cms\Models\Datasource::find($relation->relation_datasource_id)->table;
              ?>
                @if($relation->relation_type=="hasOne")
                    @if($relationTable == 'pages' && $item->{'pages_id'}) <!--datasource 1 = pages table -->
                    <a href="{{ route('pages/edit', $item->{'pages_id'}) }}" target="_blank" class="btn btn-xs "><i class="fa fa-external-link"></i> Editar {{ $relation->relation_description }}</a>
                    @endif
                @endif
            @endforeach
            <a href="{{ route('cms/ds/edit', array($datasource->id, $item->id)) }}" class="btn btn-xs btn-default">
            @if(array_key_exists($datasource->table.'.update', $_groupPermissions))
                @lang('cms::button.edit')
            @else
                @lang('cms::button.view') 
            @endif
            </a>
            @if(array_key_exists($datasource->table.'.delete', $_groupPermissions))
            <a class="btn btn-xs btn-danger" data-msg="Confirma eliminar o registo?" data-reply="" data-toggle="modal" data-descr="{{ $item->id }}" data-url="{{ route('cms/ds/delete', array($datasource->id, $item->id)) }}" href="#modal-confirm">@lang('cms::button.delete')</a>
            @endif
        </div>
    @if(count($subItems)>0)
        <input type="checkbox" name="sub-group-{{ $item->id }}" id="sub-group-{{ $item->id }}">
        <i class="fa fa-chevron-right arrow"></i><label class="has-children" for="sub-group-{{ $item->id }}">{{ $item->{$datasource->config()[0]->name} }}</label>
        <ul class="list-group">
        @foreach ($subItems as $subitem)
            @include('ocms::ds._treeview-menuitem', array('item' => $subitem))
        @endforeach
        </ul>
    @else
        <label>{{ $item->{$datasource->config()[0]->name} }}</label>
    @endif
</li>