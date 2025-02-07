<?php

/**
 * Fuel is a fast, lightweight, community driven PHP 5.4+ framework.
 *
 * @package    Fuel
 * @version    1.8.2
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2019 Fuel Development Team
 * @link       https://fuelphp.com
 */

return array(
	/**
	 * -------------------------------------------------------------------------
	 *  Default route
	 * -------------------------------------------------------------------------
	 *
	 */

	'_root_' => 'user/index',

	/**
	 * -------------------------------------------------------------------------
	 *  Page not found
	 * -------------------------------------------------------------------------
	 *
	 */

	'_404_' => 'welcome/404',

	/**
	 * -------------------------------------------------------------------------
	 *  Example for Presenter
	 * -------------------------------------------------------------------------
	 *
	 *  A route for showing page using Presenter
	 *
	 */

	'hello(/:name)?' => array('welcome/hello', 'name' => 'hello'),

	// url => fnc in controllers
	'prefecture/(:num)' => 'user/index/prefecture/$1',
	'hotel/(:num)' => 'user/index/hotel/$1',

	//admin
	'admin' => 'admin/hotel/index',

	'admin/prefecture' => 'admin/prefecture/index',
	'admin/prefecture/create' => 'admin/prefecture/create',
	'admin/hotel/create' => 'admin/hotel/create',
	'admin/hotel/update/(:num)' => 'admin/hotel/update/$1',
	'admin/hotel/status/(:num)' => 'admin/hotel/status/$1',
	// 'admin/user' => 'admin/user',


);
