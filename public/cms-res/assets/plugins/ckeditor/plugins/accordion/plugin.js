/**
 * Copyright (c) 2016, INSOMNIA - Miguel Pereira. All rights reserved.
 *
 * Plugin inserting accordion elements into the CKEditor editing area.
 *
 */

// Register the plugin within the editor.
CKEDITOR.plugins.add( 'accordion', {

	// Register the icons.
	icons: 'accordion',

	// The plugin initialization logic goes inside this method.
	init: function( editor ) {

		// Define an editor command that opens our dialog window.
		editor.addCommand( 'accordion', new CKEDITOR.dialogCommand( 'accordionDialog' ) );

		// Create a toolbar button that executes the above command.
		editor.ui.addButton( 'Accordion', {

			// The text part of the button (if available) and the tooltip.
			label: 'Inserir Accordion',

			// The command to execute on click.
			command: 'accordion',

			// The button placement in the toolbar (toolbar group name).
			toolbar: 'insert'
		});

		if ( editor.contextMenu ) {
			
			// Add a context menu group with the Edit Abbreviation item.
			editor.addMenuGroup( 'accordionGroup' );
			editor.addMenuItem( 'accordionItem', {
				label: 'Propriedades do Accordion',
				icon: this.path + 'icons/accordion.png',
				command: 'accordion',
				group: 'accordionGroup'
			});

			editor.contextMenu.addListener( function( element ) {
				if ( element.getAscendant( 'accordion', true ) ) {
					return { accordionItem: CKEDITOR.TRISTATE_OFF };
				}
			});
		}

		// Register our dialog file -- this.path is the plugin folder path.
		CKEDITOR.dialog.add( 'accordionDialog', this.path + 'dialogs/accordion.js' );
	}
});
