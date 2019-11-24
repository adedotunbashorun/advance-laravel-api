<?php

return [
	'name' => 'Transactions',
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
	'form'=>'Transactions\Forms\TransactionsForm',
	'permissions'=>[
		'transactions' => [
			'index',
			'create',
			'store',
			'show',
			'update',
			'destroy',
		],
	]
];
