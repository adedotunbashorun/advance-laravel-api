<?php

return [
	'name' => 'History',
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
	'form'=>'History\Forms\HistoryForm',
	'permissions'=>[
		'history' => [
			'index',
			'create',
			'store',
			'show',
			'update',
			'destroy',
		],
	]
];
