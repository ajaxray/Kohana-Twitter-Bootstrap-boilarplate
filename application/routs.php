<?php
/**
 * List of routs to be included in bootetrap
 *
 * Author: Anis uddin Ahmad <anisniit@gmail.com>
 * Created On: 3/30/12 6:41 PM
 */

Route::set('default', '(<controller>(/<action>(/<id>(/<title>))))')
	->defaults(array(
		'controller' => 'welcome',
		'action'     => 'index',
	));

Route::set('catch_all', '<path>', array('path' => '.+'))
	->defaults(array(
		'controller' => 'errors',
		'action'     => '404',
	));