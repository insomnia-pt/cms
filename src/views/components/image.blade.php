<input class="form-control inline image" type="text" name="{{ $component['name'] }}" id="{{ str_replace(['[',']'], '_', $component['name']) }}" data-limit="{{ $component['limit'] }}" value="{{ $component['data'] }}" readonly />
