<?php
function clean($str)
{
	return stripslashes(htmlentities($str , ENT_QUOTES , 'UTF-8'));		
}	

class Template extends Object
{
	/**
	* vars 
	*
	* @access : private
	**/
	private $vars  = array();
	
	/**
	* separator title
	*
	**/
	private $separator   = ' - ';
	
	/**
	*  default theme
	*
	**/
	public  $theme = 'default';
	
	
	/** 
	*  
	*/
	private $controller;
	
	
	/**
	* constructor 
	*
	* set Controller name
	*/
	function __construct($controller)
	{
		$this->controller = $controller;	
		
	}
	
	/**
	* get vars
	*/
	public function __get($name) {
		return $this->vars[$name];
	}
	
	/**
	* set vars to use in view
	*
	*/
	public function __set($name, $value) 
	{
		$this->vars[$name] = $value;
	}
	
	
	/**
	* create elements
	*/
	function elem($elem , $vars)
	{
		extract($vars);
		ob_start();
		include('app/views/Elements/'.$elem.'.php');
		$this->set('elem'.ucfirst($elem) , ob_get_clean());
		return ob_get_clean();
	}
	
	/**
	* render and show view in controllers name directory
	*
	*/
	public function view($title = '' , $tp = true)
	{
		//get action called view
		$b = debug_backtrace();
		$action = ($b[1]['function']);
		
		//check view exists
		$view = 'app/views/'.$this->controller.'/'.$action.'.php';
		if(!file_exists($view))
			die('404 not Found');
		
		ob_start();
		include($view);
		if($tp == true )
		{
				$this->content = ob_get_clean();
				echo $this->render();
		}
		else	
		{
				echo   ob_get_clean();	 
		}
	}
	
	/**
	* render view in layout
	*/
	private function render($title = '')
	{ 
		ob_start();
		include("app/layout/$this->theme/index.php");
		echo ob_get_clean();
	}
	
	/**
	* Set layout title
	*
	*@access private
	*/
	private function setTitle($title = '')
	{
		if(!empty($title))
		{
			if(isset($this->vars['title']))
				$this->title = $this->title .$this->separator.$title;
			else
				$this->title = $title;		
		}
	}
	
	/**
	* Set Separator to separate titles
	**/
	private function setSeparator($s = ' - ')
	{
		$this->separator = $s;		
	}
}