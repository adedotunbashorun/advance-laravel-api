<?php

return [
	'name' => 'Settings',
	'order' => [
		'id' => 'asc',
	],
	'sidebar' => [
		'weight' => 2,
		'icon' => 'fa fa-file',
	],
	'th' => ['title','status'],
	'columns'=>[
            ['data'=>'title','name'=>'title'],
            ['data'=>'status','name'=>'status'],
            ['data'=>'action','name'=>'action'],
     ],
	'form'=>'Settings\Forms\SettingsForm',
	'permissions'=>[
		'settings' => [
			'index',
			'create',
			'store',
			'show',
			'update',
			'destroy',
		],
	]
];
