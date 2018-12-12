

  var filerTemplatePreview = {
    box: '<ul class="jFiler-items-list jFiler-items-grid"></ul>',
    item: '<li class="jFiler-item">\
                <div class="jFiler-item-container">\
                    <div class="jFiler-item-inner">\
                        <div class="jFiler-item-thumb">\
                            <div class="jFiler-item-status"></div>\
                            <div class="jFiler-item-thumb-overlay">\
                    					<div class="jFiler-item-info">\
                    						<div style="display:table-cell;vertical-align: middle;">\
                    							<span class="jFiler-item-title"><b title="{{fi-name}}">{{fi-name}}</b></span>\
                    							<span class="jFiler-item-others">{{fi-size2}}</span>\
                    						</div>\
                    					</div>\
                    				</div>\
                            {{fi-image}}\
                        </div>\
                        <div class="jFiler-item-assets jFiler-row">\
                            <ul class="list-inline pull-left">\
                                <li>{{fi-progressBar}}</li>\
                            </ul>\
                            <ul class="list-inline pull-right">\
                                <li><a class="icon-jfi-trash jFiler-item-trash-action"></a></li>\
                            </ul>\
                        </div>\
                    </div>\
                </div>\
            </li>',
    itemAppend: '<li class="jFiler-item">\
                    <div class="jFiler-item-container">\
                        <div class="jFiler-item-inner">\
                            <div class="jFiler-item-thumb">\
                                <div class="jFiler-item-status"></div>\
                                <div class="jFiler-item-thumb-overlay">\
                    							<div class="jFiler-item-info">\
                    								<div style="display:table-cell;vertical-align: middle;">\
                                    <a href="{{fi-url}}" target="_blank">\
                    									<span class="jFiler-item-title"><b title="{{fi-name}}">{{fi-name}}</b></span>\
                    									<span class="jFiler-item-others">{{fi-size2}}</span>\
                                      </a>\
                    								</div>\
                    							</div>\
                    						</div>\
                                {{fi-image}}\
                            </div>\
                            <div class="jFiler-item-assets jFiler-row">\
                                <ul class="list-inline pull-left">\
                                    <li><span class="jFiler-item-others"><i class="fa fa-arrows-alt" style="cursor:grab"></i> &nbsp; {{fi-icon}}</span></li>\
                                </ul>\
                                <ul class="list-inline pull-right">\
                                    <li><a class="icon-jfi-trash jFiler-item-trash-action"></a></li>\
                                </ul>\
                            </div>\
                        </div>\
                    </div>\
                </li>',
    progressBar: '<div class="bar"></div>',
    itemAppendToEnd: false,
    removeConfirmation: true,
    _selectors: {
        list: '.jFiler-items-list',
        item: '.jFiler-item',
        progressBar: '.bar',
        remove: '.jFiler-item-trash-action'
    }
  };
