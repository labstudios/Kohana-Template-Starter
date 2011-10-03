<?php defined('SYSPATH') or die('No direct script access.');

abstract class Controller_Master extends Controller {

    const ASSET_PATH = "assets/";
    const SKELETON = "skeleton";
    protected $page = "";
    private $_css = array("reset", 'screen');
    private $_pcss = array();
    private $_js = array("mootools_core", "tracer");
    private $_phpjs = array();
    protected $content = "";
    protected $title = "Default Title";
    protected $meta = "";
    protected $shown = false;
    protected $ajax = false;

    public function Controller_Master($request, $response)
    {
        parent::__construct($request, $response);
    }
    
    public function __get($key)
    {
        switch($key)
        {
            case "css":
                return $this->compileCSS();
            break;
            case "pcss":
                return $this->compilePrintCSS();
            break;
            case "js":
                return $this->compileJS();
            break;
            case "phpjs":
            	return $this->compilePHPJS();
            break;
            default:
                return null;
            break;
        }
    }
    
    public function __set($key, $val)
    {
        switch($key)
        {
            case "css":
                $this->addCSS($val);
            break;
            case "pcss":
                $this->addPCSS($val);
            break;
            case "js":
                $this->addJS($val);
            break;
            case "phpjs":
                $this->addPHPJS($val);
            break;
            default:
                //$this->$key = $val;
            break;
        }
    }
    
    protected function addJS($file)
    {
        $this->_js[] = $file;
    }
    
    protected function addPHPJS($file)
    {
    	$this->_phpjs[] = $file;
    }
    
    protected function addCSS($file)
    {
        $this->_css[] = $file;
    }
    
    protected function addPCSS($file)
    {
        $this->_pcss[] = $file;
    }
    
    protected function show()
    {
    	$this->page = $this->page == "" ? View::factory(self::SKELETON):$this->page;
        $this->page->set('title', $this->title);
        $this->page->set('css', $this->css);
        $this->page->set('pcss', $this->pcss);
        $this->page->set('js', $this->js.$this->phpjs);
        $this->page->set('meta', $this->meta);
        $this->page->set('content', $this->content);
        $this->shown = true;
		$this->response->body($this->page);
    }
    
    private function compileCSS()
    {
        $ret = "";
        foreach($this->_css as $file)
        {
            $full_path = self::ASSET_PATH."css/".$file.".css";
            $ret .= "\t".HTML::style($full_path, array('media' => 'screen'))."\r\n";
        }
        return $ret;
    }
    
    private function compilePrintCSS()
    {
        $ret = "";
        foreach($this->_pcss as $file)
        {
            $full_path = self::ASSET_PATH."css/".$file.".css";
            $ret .= "\t".HTML::style($full_path, array('media' => 'print'))."\r\n";
        }
        return $ret;
    }
    
    private function compileJS()
    {
        $ret = "";
        foreach($this->_js as $file)
        {
            $full_path = self::ASSET_PATH."js/".$file.".js";
            $ret .= "\t".HTML::script($full_path)."\r\n";
        }
        return $ret;
    }
    
    private function compilePHPJS()
    {
        $ret = "";
        foreach($this->_phpjs as $file)
        {
            $full_path = self::ASSET_PATH."js/".$file.".php";
            $ret .= "\t".HTML::script($full_path)."\r\n";
        }
        return $ret;
    }
    
    public function __destruct()
    {
        if($this->content != "" && !$this->shown && !$this->ajax)
        {
            $this->show();
        }
        else if($this->content != "" && $this->ajax)
        {
        	$this->response->body($this->content);
        }
    }
    
    public static function cleanData($data, $method = FILTER_SANITIZE_STRING)
    {
        if(is_array($data))
        {
            return (Object) filter_var_array($data, $method);
        }
        else
        {
            return filter_var($data, $method);
        }
    }
} // End Main
