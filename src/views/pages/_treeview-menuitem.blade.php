<?php
    $subPages = $pages->filter(function($subpage) use ($item) {
        return $subpage->id_parent == $item->id;
    })->values();
?>

<li class="list-group-item  @if (count($subPages)>0) has-children @endif">
        <div class="actions pull-right">
            @if($item->editable)
                <a href="{{ route('pages/edit', $item->id) }}" class="btn btn-xs btn-default">
                @if(CMS_Helper::checkPermission('pages.update'))
                    @lang('cms::button.edit')
                @else
                    @lang('cms::button.view')
                @endif
                </a>
            @else
                <a href="{{ route('pages/edit', $item->id) }}" class="btn btn-xs btn-default">
                    @lang('cms::button.view')
                </a>
            @endif
            @if(!$item->system)
                @if(CMS_Helper::checkPermission('pages.delete'))
                <a class="btn btn-xs btn-danger" data-msg="Confirma eliminar o registo?" data-reply="" data-toggle="modal" data-descr="{{ $item->id }}" data-url="{{ route('pages/delete', $item->id) }}" href="#modal-confirm">@lang('cms::button.delete')</a>
                @endif
            @endif
        </div>
    @if (count($subPages)>0)
        <input type="checkbox" name="sub-group-{{ $item->id }}" id="sub-group-{{ $item->id }}">
        <i class="fa fa-chevron-right arrow"></i><label class="has-children" for="sub-group-{{ $item->id }}">{{ $item->title }}</label>
        <ul class="list-group">
        @foreach ($subPages as $pageitem)
            @include('cms::pages._treeview-menuItem', array('item' => $pageitem))
        @endforeach
        </ul>

    @else
        <label>{{ $item->title }}</label>
    @endif
</li>