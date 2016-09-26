<select class="form-control" name="{{ $component['name'] }}" id="{{ $component['name'] }}">
  @foreach(explode(';', @$component['items']) as $fieldOption)
    <option value="{{ @explode(',', $fieldOption)[0] }}" {{ $component['data']==@explode(',', $fieldOption)[0]?'selected':'' }}>{{ @explode(',', $fieldOption)[1] }}</option>
  @endforeach
</select>
