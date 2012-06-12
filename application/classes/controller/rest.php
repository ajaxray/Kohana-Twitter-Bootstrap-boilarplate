<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Abstract Controller class for RESTful controller mapping. Supports GET, PUT,
 * POST, and DELETE. By default, these methods will be mapped to these actions:
 *
 * GET
 * :  Mapped to the "index" action, lists all objects
 *
 * POST
 * :  Mapped to the "create" action, creates a new object
 *
 * PUT
 * :  Mapped to the "update" action, update an existing object
 *
 * DELETE
 * :  Mapped to the "delete" action, delete an existing object
 *
 * Additional methods can be supported by adding the method and action to
 * the `$_action_map` property.
 *
 * [!!] Using this class within a website will require heavy modification,
 * due to most web browsers only supporting the GET and POST methods.
 * Generally, this class should only be used for web services and APIs.
 *
 * @package    Kohana
 * @category   Controller
 * @author     Kohana Team
 * @copyright  (c) 2009-2011 Kohana Team
 * @license    http://kohanaframework.org/license
 */
abstract class Controller_REST extends Controller {


    public $resource;
    public $id;


    /**
	 * @var  array  REST types
	 */
	protected $_action_map = array
	(
		Http_Request::GET    => 'index',
		Http_Request::PUT    => 'update',
		Http_Request::POST   => 'create',
		Http_Request::DELETE => 'delete',
	);

	/**
	 * @var  string  requested action
	 */
	protected $_action_requested = '';

	/**
	 * Checks the requested method against the available methods. If the method
	 * is supported, sets the request action from the map. If not supported,
	 * the "invalid" action will be called.
	 */
	public function before()
	{
        Kohana::$log->add(Log::INFO, '['. $this->request->method().'] CALLED WITH: ' . $_SERVER['REQUEST_URI']);

		$this->_action_requested = $this->request->action();

		$method = Arr::get($_SERVER, 'HTTP_X_HTTP_METHOD_OVERRIDE', $this->request->method());

		if ( ! isset($this->_action_map[$method]))
		{
			$this->request->action('invalid');
		}
		else
		{
			$this->request->action($this->_action_map[$method]);
		}

        $this->resource = $this->request->controller();
        $this->id   = $this->_action_requested;

		return parent::before();
	}

	/**
	 * undocumented function
	 */
	public function after()
	{
		if (in_array(Arr::get($_SERVER, 'HTTP_X_HTTP_METHOD_OVERRIDE', $this->request->method()), array(
			Http_Request::PUT,
			Http_Request::POST,
			Http_Request::DELETE)))
		{
			$this->response->headers('cache-control', 'no-cache, no-store, max-age=0, must-revalidate');
		}
	}

	/**
	 * Sends a 405 "Method Not Allowed" response and a list of allowed actions.
	 */
	public function action_invalid()
	{
		// Send the "Method Not Allowed" response
		$this->response->status(405)
			->headers('Allow', implode(', ', array_keys($this->_action_map)));
	}

    protected function _findAction()
    {
        $method = '_' . Inflector::camelize($this->action);
        return (method_exists($this, $method))? array($this, $method) : false;
    }

}
