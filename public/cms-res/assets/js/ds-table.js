var dataTable;

function renderDataTable(element, options){
  if(dataTable) dataTable.destroy();

  this.defaults = {
      "dom": "<'row'<'col-sm-6'><'col-sm-6'>r>t<'row'<'col-sm-7'li><'col-sm-5'p>>",
      "oLanguage": {
        "sEmptyTable":"Não existem dados",
          "sLengthMenu": "_MENU_",
          "sInfo": "_START_ / _END_ de _TOTAL_ registos",
          "oPaginate": {
              "sPrevious": "Anterior",
              "sNext": "Seguinte"
          }
      },
      columnDefs: [
          { targets: 0, visible: false },
          { targets: 'nosort', orderable: false },
          { targets: 'nosort-temp', orderable: false }
      ]

  };

  this.settings = $.extend({}, this.defaults, options);
  dataTable = element.DataTable(this.settings);

}

renderDataTable($('#main_table'), null);

var reorderStatus = 0;
$("#bt-tableorder").click(function(){
  $('#ds-orderlist').val((dataTable.rows().ids().join(",")).replace(/row-/g,''));
  if(reorderStatus){
    $('#main_table th').removeClass('nosort-temp');
    renderDataTable($('#main_table'), null);
    $(this).find('span').text($(this).data('status-off'));
    $('#form-savetableorder').fadeOut(100);
    reorderStatus=0;
  } else {
    $('#main_table th').addClass('nosort-temp');
    renderDataTable($('#main_table'), { rowReorder: { selector: 'tr' } });
    $(this).find('span').text($(this).data('status-on'));
    $('#form-savetableorder').fadeIn(100);
    reorderStatus=1;
  }
  $('#main_table').toggleClass('reorder');
  $(this).toggleClass('btn-default btn-white');
});

dataTable.on( 'row-reorder', function ( e, diff, edit ) {
  setTimeout(function() {
    $('#ds-orderlist').val((dataTable.rows().ids().join(",")).replace(/row-/g,''));
  },10);
});


$("#dataTable1filter").keyup(function() {
   dataTable.search(this.value).draw();
});


jQuery('#main_table_wrapper .dataTables_filter input').addClass("form-control"); // modify table search input
jQuery('#main_table_wrapper .dataTables_length select').addClass("form-control"); // modify table per page dropdown
