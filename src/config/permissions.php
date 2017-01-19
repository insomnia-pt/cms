<?php

return array(

	'Geral' => array(
		array(
			'permission' => 'backoffice',
			'label'      => 'Acesso ao Backoffice',
		),
		array(
			'permission' => 'component.ckeditor.adv',
			'label'      => 'Editor Texto Avançado',
		),
	),
	
	'Grupos' => array(
		array(
			'permission' => 'groups.view',
			'label'      => 'Visualização',
		),
		array(
			'permission' => 'groups.create',
			'label'      => 'Criação',
		),
		array(
			'permission' => 'groups.update',
			'label'      => 'Alteração',
		),
		array(
			'permission' => 'groups.delete',
			'label'      => 'Remoção',
		),
	),

	'Utilizadores' => array(
		array(
			'permission' => 'users.view',
			'label'      => 'Visualização',
		),
		array(
			'permission' => 'users.create',
			'label'      => 'Criação',
		),
		array(
			'permission' => 'users.update',
			'label'      => 'Alteração',
		),
		array(
			'permission' => 'users.group',
			'label'      => 'Alteração do Grupo',
		),
		array(
			'permission' => 'users.delete',
			'label'      => 'Remoção',
		),
	),

	'Datasources' => array(
		array(
			'permission' => 'datasources.view',
			'label'      => 'Visualização',
		),
		array(
			'permission' => 'datasources.create',
			'label'      => 'Criação',
		),
		array(
			'permission' => 'datasources.update',
			'label'      => 'Alteração',
		),
		array(
			'permission' => 'datasources.delete',
			'label'      => 'Remoção',
		),
	),

	'Gestão Ficheiros' => array(
		array(
			'permission' => 'filebrowser.view',
			'label'      => 'Visualização',
		),
	),

);
