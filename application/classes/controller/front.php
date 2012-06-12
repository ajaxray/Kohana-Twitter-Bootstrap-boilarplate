<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Generic parent for all template based controllers
 *
 * Author: Anis uddin Ahmad <anisniit@gmail.com>
 * Created On: 3/30/12 3:08 PM
 */

class Controller_Front extends Controller_Template
{

  public $template = 'layout_hero';

  /**
   * Session instance
   * @var Session
   */
  protected $_session;
/**
 *Is logged in check
 * @var boolean
 */
  public $isLoggedIn;
    /**
     * Database instance for regular app
     * @var Database
     */
    protected $_db;

    /**
     * Basic application configs
     * @var Kohana_Config
     */
    protected $_config;

    // Flag to determine if request is an ajax call
    protected $_isAjax = false;

    // Params from active request
    protected $_params;

    // TO keep user's momiloop login info
    protected $_userData;

  /**
   * The before() method is called before your controller action.
   * In our template controller we override this method so that we can
   * set up default values. These variables are then available to our
   * controllers if they need to be modified.
   */
  public function before()
  {
        parent::before();

        $this->_config = Kohana::$config->load('app');

        $this->_session = Session::instance();
        //$this->_db = Database::instance();
        $this->_params = array_merge($_GET, $this->request->param());

        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $this->_isAjax = true;
        }

        //var_dump($this->user); die;

        if ($this->auto_render)
        {
            // Initialize empty values
            $this->template->content = 'Content not set!';
            $this->template->meta    = array(
                'keywords'    => $this->_config['meta']['keywords'],
                'description' => $this->_config['meta']['description'],
            );


            $this->template->styles = array();
            $this->template->scripts = array();

        }
  }

  /**
   * The after() method is called after your controller action.
   * In our template controller we override this method so that we can
   * make any last minute modifications to the template before anything
   * is rendered.
   */
    public function after()
    {
        // If ajax call, show only the content, no layout needed
        if($this->_isAjax){
            $this->request->response()->body($this->template->content);
            return;
        }

        if ($this->auto_render)
        {
            $styles = array(
                URL::site('/assets/css/bootstrap.min.css') => 'screen',
                //URL::site('/assets/css/bootstrap-responsive.min.css') => 'screen',
                URL::site('/assets/css/style.css') => 'screen',
            );

            $scripts = array(
                URL::site('assets/js/libs/jquery-1.7.1.min.js'),
                URL::site('assets/js/libs/bootstrap.min.js'),
                URL::site('assets/js/libs/bootstrap/bootstrap-transition.js'),
                URL::site('assets/js/libs/bootstrap/bootstrap-alert.js'),
                //URL::site('assets/js/libs/bootstrap/bootstrap-modal.js'),
                //URL::site('assets/js/libs/bootstrap/bootstrap-dropdown.js'),
                //URL::site('assets/js/libs/bootstrap/bootstrap-scrollspy.js'),
                //URL::site('assets/js/libs/bootstrap/bootstrap-tab.js'),
                //URL::site('assets/js/libs/bootstrap/bootstrap-tooltip.js'),
                //URL::site('assets/js/libs/bootstrap/bootstrap-popover.js'),
                //URL::site('assets/js/libs/bootstrap/bootstrap-button.js'),
                //URL::site('assets/js/libs/bootstrap/bootstrap-collapse.js'),
                //URL::site('assets/js/libs/bootstrap/bootstrap-carousel.js'),
                //URL::site('assets/js/libs/bootstrap/bootstrap-typeahead.js'),
            );

            $this->template->styles = array_merge($styles, $this->template->styles);
            $this->template->scripts = array_merge($scripts, $this->template->scripts);
        }

        $this->template->title = empty($this->template->title)?
                                    $this->_config['site']['name'] :
                                    $this->template->title . $this->_config['site']['title_suffix'];

        parent::after();
    }


    protected function _getPagination($total)
    {
        if($total instanceof ORM){
            $model = $total;
            $pagination = $this->_getPagination($model->reset(FALSE)->count_all());

            $model->offset($pagination->offset);
            $model->limit($pagination->items_per_page);

            return $pagination;
        }

        return Pagination::factory(array(
        'total_items'    => $total,
        'items_per_page' => $this->_config['post']['per_page'],
        //'view'           => 'pagination/adminus'
    ));
    }

    protected function _redirectWithFlash($message, $url = '/', $messageType = Message::ERROR)
    {
        Message::set($messageType, $message);
        Request::instance()->redirect($url);
    }
}
