<?php
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP
 *
 * This front is released under the MIT License (MIT)
 *
 * Copyright (c) 2014 - 2017, British Columbia Institute of Technology
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package	CodeIgniter
 * @author	EllisLab Dev Team
 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc. (https://ellislab.com/)
 * @copyright	Copyright (c) 2014 - 2017, British Columbia Institute of Technology (http://bcit.ca/)
 * @license	http://opensource.org/licenses/MIT	MIT License
 * @link	https://codeigniter.com
 * @since	Version 1.0.0
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Application Controller Class
 *
 * This class object is the super class that every library in
 * CodeIgniter will be assigned to.
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Libraries
 * @author		EllisLab Dev Team
 * @link		https://codeigniter.com/user_guide/general/controllers.html
 */
class CI_Controller {

	/**
	 * Reference to the CI singleton
	 *
	 * @var	object
	 */
	private static $instance;
	public $current_user = 0;
	protected $data = array();

	/**
	 * Class constructor
	 *
	 * @return	void
	 */
	public function __construct()
	{
		self::$instance =& $this;

		// Assign all the class objects that were instantiated by the
		// bootstrap file (CodeIgniter.php) to local class variables
		// so that CI can run as one big super object.
		foreach (is_loaded() as $var => $class)
		{
			$this->$var =& load_class($class);
		}

		$this->load =& load_class('Loader', 'core');
		$this->load->initialize();
		log_message('info', 'Controller Class Initialized');

		$this->load->add_package_path(APPPATH.'third_party/ion_auth/');
		$this->load->library('ion_auth');
		log_message('info', 'Ion Auth Initialized');


        if ( $this->ion_auth->logged_in() ) {
            $this->current_user = $this->ion_auth->user()->row()->id;
            log_message('info', 'Current User ID Initialized');

            // Getting SEO info
            $this->data['seo_title'] = 'DNA Trucking LLC';
            $this->data['seo_description'] = 'DNA Trucking LLC';
            $this->data['seo_keywords'] = 'DNA Trucking LLC';
            log_message('info', 'View info Initialized');
        }
	}

	// --------------------------------------------------------------------

	/**
	 * Get the CI singleton
	 *
	 * @static
	 * @return	 object
	 */
	public static function &get_instance()
	{
		return self::$instance;
	}

	/**
	 * Render main template
	 *
	 * @return	 void
	 */
	protected function __render()
	{
        $this->load->view('main_template', $this->data);
	}

    /**
     * Метод преобразования sql-массива
     *
     * Преобразовывает из sql результата удобный ассоциативный массив ключ=значение
     *
     * @param array                 $array - переменная для проверки
     * @param string                $key - указания какое поле будет ключом
     * @param string                $value - указания какое поле будет значением
     * @return false | array    $new_array
     */
    protected function key_value_array($array, $key, $value){
        if ( ! is_array($array) OR ! is_array($array[0]) ) return false;
        $new_array = array();
        foreach($array as $arr) $new_array[$arr[$key]] = $arr[$value];
        return $new_array;
    }

	/**
	 * Prevent xss
	 *
	 * @param string
	 * @return	 string
	 */
	public function xss($string) {
		return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
	}

}
