<?php

return array(

	'Geral' => array(
		array(
			'permission' => 'cms',
			'label'      => 'Acesso ao CMS',
		),
		array(
			'permission' => 'component.ckeditor.adv',
			'label'      => 'Editor Texto Avançado',
		),
	),
	
	'Grupos' => array(
		array(
			'permission' => 'groups.view',
			'label'      => 'Visualizar',
		),
		array(
			'permission' => 'groups.create',
			'label'      => 'Criar',
		),
		array(
			'permission' => 'groups.update',
			'label'      => 'Alterar',
		),
		array(
			'permission' => 'groups.delete',
			'label'      => 'Eliminar',
		),
	),

	'Utilizadores' => array(
		array(
			'permission' => 'users.view',
			'label'      => 'Visualizar',
		),
		array(
			'permission' => 'users.create',
			'label'      => 'Criar',
		),
		array(
			'permission' => 'users.update',
			'label'      => 'Alterar',
		),
		array(
			'permission' => 'users.group',
			'label'      => 'Alteração do Grupo',
		),
		array(
			'permission' => 'users.delete',
			'label'      => 'Eliminar',
		),
	),

	'Datasources' => array(
		array(
			'permission' => 'datasources.view',
			'label'      => 'Visualizar',
		),
		array(
			'permission' => 'datasources.create',
			'label'      => 'Criar',
		),
		array(
			'permission' => 'datasources.update',
			'label'      => 'Alterar',
		),
		array(
			'permission' => 'datasources.delete',
			'label'      => 'Eliminar',
		),
	),

	'Menus' => array(
		array(
			'permission' => 'menus.view',
			'label'      => 'Visualizar',
		),
		array(
			'permission' => 'menus.create',
			'label'      => 'Criar',
		),
		array(
			'permission' => 'menus.update',
			'label'      => 'Alterar',
		),
		array(
			'permission' => 'menus.delete',
			'label'      => 'Eliminar',
		),
	),

	'Gestão Ficheiros' => array(
		array(
			'permission' => 'filebrowser.view',
			'label'      => 'Visualizar',
		),
	),

	'Definições' => array(
		array(
			'permission' => 'settings.view',
			'label'      => 'Visualizar',
		),
		array(
			'permission' => 'settings.update',
			'label'      => 'Alterar',
		),
	),


);
