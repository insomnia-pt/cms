<?php
    $subItems = $dsItems->filter(function($subitem) use ($item) {
        return $subitem->id_parent == $item->id;
    })->values();
?>


  <li class="dd-item @if(count($subItems)>0) dd-collapsed @endif" data-id="{{ $item->id }}">
    <div class="dd-handle"></div>
    <div class="actions pull-right" style="margin: 8px;">
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
        <a class="btn btn-xs btn-danger" data-msg="Confirma eliminar o registo?" data-reply="" data-toggle="modal" data-descr="{{ $datasource->config()[0]->multilang ? @json_decode($item->{$datasource->config()[0]->name})->{$settings->language} : $item->{$datasource->config()[0]->name} }}" data-url="{{ route('cms/ds/delete', array($datasource->id, $item->id)) }}" href="#modal-confirm">@lang('cms::button.delete')</a>
      @endif
    </div>
    @if(count($subItems)>0)
      {{ $datasource->config()[0]->multilang ? @json_decode($item->{$datasource->config()[0]->name})->{$settings->language} : $item->{$datasource->config()[0]->name} }}

      <ol class="dd-list ">
      @foreach ($subItems as $subitem)
        @include('cms::ds._treeview-menuitem', array('item' => $subitem))
      @endforeach
      </ol>
    @else
      {{ $datasource->config()[0]->multilang ? @json_decode($item->{$datasource->config()[0]->name})->{$settings->language} : $item->{$datasource->config()[0]->name} }}
    @endif
  </li>
