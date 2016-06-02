/**
 * Copyright (c) 2014-2016, CKSource - Frederico Knabben. All rights reserved.
 * Licensed under the terms of the MIT License (see LICENSE.md).
 *
 * The abbr plugin dialog window definition.
 *
 * Created out of the CKEditor Plugin SDK:
 * http://docs.ckeditor.com/#!/guide/plugin_sdk_sample_1
 */

// Our dialog definition.
CKEDITOR.dialog.add( 'accordionDialog', function( editor ) {
	return {

		// Basic properties of the dialog window: title, minimum size.
		title: 'Propriedades do Accordion',
		minWidth: 400,
		minHeight: 200,

		// Dialog window content definition.
		contents: [
			{
				// Definition of the Basic Settings dialog tab (page).
				id: 'tab-basic',
				label: 'Basic Settings',
				

				// The tab content.
				elements: [
					{
						type: 'text',
                        id: 'number',
                        label: 'Número de secções a adicionar',
                        validate: CKEDITOR.dialog.validate.notEmpty( "Não pode ficar vazio" ),

						// Called by the main setupContent method call on dialog initialization.
						setup: function( element ) {
							this.setValue('0');
						},

						// // Called by the main commitContent method call on dialog confirmation.
						// commit: function( element ) {
						// 	element.setAttribute('data-num', this.getValue() );
						// }
					},
					{
						// Another text field for the accordion element id.
						type: 'html',
                    	html: '<div id="accordionlist"></div>',

						// Called by the main setupContent method call on dialog initialization.
						setup: function( element ) {
							// this.setValue('<div>Teste</div>');

						},

						// Called by the main commitContent method call on dialog confirmation.
						commit: function ( element ) {
							// var id = this.getValue();
							// if ( id )
							// 	element.setAttribute( 'id', id );
							// else if ( !this.insertMode )
							// 	element.removeAttribute( 'id' );
						}
					}
				]
			}
		],

		// Invoked when the dialog is loaded.
		onShow: function() {

			// Get the selection from the editor.
			var selection = editor.getSelection();

			// Get the element at the start of the selection.
			var element = selection.getStartElement();

			// Get the <accordion> element closest to the selection, if it exists.
			if ( element )
				element = element.getAscendant( 'accordion', true );

			// Create a new <accordion> element if it does not exist.
			if ( !element || element.getName() != 'accordion' ) {
				element = editor.document.createElement( 'accordion' );

				// Flag the insertion mode for later use.
				this.insertMode = true;
			}
			else {
				// lista sections para remover
				var document = this.getElement().getDocument();
			    var accordionlist = document.getById('accordionlist');
			    var elementnumsections = document.getById('number');
			    // elementnumsections.setLabel('Teste');
			    if (accordionlist) {

			    	var arr = editor.document.$.getElementsByClassName("panel");
			    	var sections = '<br /><div style="text-align:right;">Remover</div><div style="max-height:300px;overflow:auto"><ul>';
					for (i = 0; i < arr.length; i++) {

						sections = sections + "<li style='display:block;padding:4px;border-bottom:1px solid #ddd'><input type='checkbox' class='accordionlistChecks' style='float: right;margin-right:10px' value='"+i+"' />"+arr[i].getElementsByClassName("accordion-section-name")[0].text+"</li>";

						// editor.document.$.getElementsByClassName(\"panel\")["+i+"].remove()
						// $(accordion.$.children[0]).eq("+i+").remove()
					}
					sections = sections+"</ul></div>";
			        accordionlist.setHtml(sections);

			        
			    }

				this.insertMode = false;
			}

			// Store the reference to the <accordion> element in an internal property, for later use.
			this.element = element;

			// Invoke the setup methods of all dialog window elements, so they can load the element attributes.
			if ( !this.insertMode )
				this.setupContent( this.element );


		},

		onOk: function() {

			// The context of this function is the dialog object itself.
			// http://docs.ckeditor.com/#!/api/CKEDITOR.dialog
			var dialog = this;

			// Create a new <accordion> element.
			var accordion = this.element;

			// Invoke the commit methods of all dialog window elements, so the <accordion> element gets modified.
			this.commitContent( accordion );

			if ( this.insertMode ) {

				var uniqid = Date.now();
	            intern = ""
	            for (i=0;i<parseInt(dialog.getValueOf('tab-basic','number'));i++){

	                section = "<div class='panel panel-default'><div class='panel-heading ocms-accordion_header' role='tab' ><h4 class='panel-title'><a class='accordion-section-name' role='button' data-toggle='collapse' data-parent='#accordion-"+uniqid+"' href='#accordion-"+uniqid+"_"+i+"' aria-expanded='false' aria-controls='accordion-"+uniqid+"_"+i+"'>Nome da Secção</a></h4></div><div id='accordion-"+uniqid+"_"+i+"' class='panel-collapse collapse' role='tabpanel' ><div class='panel-body ocms-accordion_content'>Insira o texto da secção do accordion aqui</div></div></div>"

	                intern = intern + section
	            }

	            accordion.setHtml('<div class="panel-group" id="accordion-'+uniqid+'" role="tablist" aria-multiselectable="true">'+ intern +'</div>');

	            editor.insertElement( accordion );
			} else {

				var document = this.getElement().getDocument();
			    var accordionlistChecks = document.$.getElementsByClassName('accordionlistChecks');			
			    
				var itemsToRemove = [];
				$.each(accordionlistChecks, function( key, item ) {
					if(item.checked) itemsToRemove.push(item.value);  
				});
				$.each(itemsToRemove.reverse(), function(key, pos) {
					editor.document.$.getElementsByClassName("panel")[pos].remove();
				});

			    
				

				var arr = editor.document.$.getElementsByClassName("panel");
				if(parseInt(dialog.getValueOf('tab-basic','number')) > 0){

					var uniqid = ($(accordion.$.children[0]).attr('id')).split('-');
					uniqid = uniqid[1];
		            intern = ""
		            var totalsections = arr.length + parseInt(dialog.getValueOf('tab-basic','number'));

		            for (i=arr.length;i<totalsections;i++){

		                section = "<div class='panel panel-default'><div class='panel-heading ocms-accordion_header' role='tab' ><h4 class='panel-title'><a class='accordion-section-name' role='button' data-toggle='collapse' data-parent='#accordion-"+uniqid+"' href='#accordion-"+uniqid+"_"+i+"' aria-expanded='false' aria-controls='accordion-"+uniqid+"_"+i+"'>Nome da Secção</a></h4></div><div id='accordion-"+uniqid+"_"+i+"' class='panel-collapse collapse' role='tabpanel' ><div class='panel-body ocms-accordion_content'>Insira o texto da secção do accordion aqui</div></div></div>"

		                intern = intern + section
		            }
		            $(accordion.$.children[0]).append(intern);

				} 
			}

				
		}
	};


});
