<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Welcome extends Controller_Front {

    public function action_index()
    {
        $this->template->content = View::factory(
                                    'welcome/index',
                                    array('title' => 'Kohana + Twitter Bootstrap boilarplate'));
    }

} // End Welcome
