<div class="input-append date col-lg-4" style="padding: 0" data-date-format="yyyy-mm-dd" data-date="{{ date('Y-m-d') }}">
  <input type="text" class="form-control" name="{{ $component['name'] }}" id="{{ $component['name'] }}" value="{{ $component['data'] }}" readonly />
  <span class="add-on"><i class="fa fa-calendar"></i></span>
</div>
