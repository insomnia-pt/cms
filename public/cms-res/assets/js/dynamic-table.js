var Script = function () {

        // begin first table
        var dataTable = $('#main_table').dataTable({
            "sDom": "<'row'<'col-sm-6'><'col-sm-6'>r>t<'row'<'col-sm-7'li><'col-sm-5'p>>",
            "sPaginationType": "bootstrap",
            "oLanguage": {
            	"sEmptyTable":"Não existem dados",
                "sLengthMenu": "_MENU_",
                "sInfo": "_START_ / _END_ de _TOTAL_ registos",
                "oPaginate": {
                    "sPrevious": "Anterior",
                    "sNext": "Seguinte"
                }
            },
            "aoColumnDefs": [{
                'bSortable': false,
                'aTargets': [0]
            }],
        });


        $("#dataTable1filter").keyup(function() {
           dataTable.fnFilter(this.value);
        }); 
       

        jQuery('#main_table_wrapper .dataTables_filter input').addClass("form-control"); // modify table search input
        jQuery('#main_table_wrapper .dataTables_length select').addClass("form-control"); // modify table per page dropdown

       
}();