<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Main class
 * 
 * The purpose of this class is to have a base structure for
 * the web site. A default header and footer view can instantly
 * be implemented into all controllers.
 * 
 * To use, simply have your controller class extend Main.
 * 
 * To set the content for the page:
 * 
 * $this->content = View::factory("view/path");
 * 
 * If the header and/or footer do need to be different
 * than the standard, then can be set in like manner.
 * 
 * $this->header = View::factory("view/path");
 * $this->footer = View::factory("view/path");
 * 
 * To include js or css files, simply set js and css.
 * Both of these can be set several times. Each time
 * they are set, the list will be added to, not replaced.
 * js and css folders will be added respectively to the
 * ASSET_PATH, so you don't need to worry about those.
 *  - Do not include the file extensions (.js and .css)
 *  - Do not use the _js variables. They are protected in
 *     case they need to be fully reset in a controller,
 *     but should not be accessed every time you add a file.
 *  - All js and css will be added in the <head> tag.
 * 
 * $this->js = "folder/file";
 * $this->js = "folder/otherfile";
 * $this->css = "folder/file";
 * 
 * If a controller needs to respond to an AJAX request, 
 * set $this->ajax = true. The skeleton will not be used.
 * Only the content will be output.
 * 
 * Ideally, only global adjustments should be made to the skeleton template.
 * If you need a different base structure for specific pages, you can 
 * set the page variable. Note that all the css, js, content, header, footer, etc.
 * will still be sent to the page view automatically.
 * 
 * $this->page = View::factory("templates/new_skeleton");
 */
abstract class Main extends Controller {

    const ASSET_PATH = "assets/";
    const SKELETON = "templates/skeleton";
    const DEFAULT_HEADER = "templates/header";
    const DEFAULT_FOOTER = "templates/footer";
    protected $page = "";
    protected $_css = array("reset", "screen");
    protected $_pcss = array();
    protected $_js = array(
        "https://ajax.googleapis.com/ajax/libs/mootools/1.4.5/mootools-yui-compressed.js", 
        "global");
    protected $_phpjs = array();
    protected $_meta = array("author" => "B.W. Allen", "description" => "", "viewport" => "width=device-width,initial-scale=1");
    protected $content = "";
    protected $header = "";
    protected $footer = "";
    protected $title = "Default Title";
    protected $shown = false;
    protected $ajax = false;
    protected $params;
    
    public function before()
    {
        $this->params = (Object) $this->request->param();
        $this->header = View::factory(self::DEFAULT_HEADER);
        $this->footer = View::factory(self::DEFAULT_FOOTER);
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
            case "meta":
                return $this->compileMeta();
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
            case "meta":
                $this->addMeta($val);
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
    
    protected function addMeta($name, $content = "")
    {
        if(is_array($name))
        {
            foreach ($name as $key => $val)
            {
                $this->_meta[$key] = $val;
            }
        }
        else
        {
            $this->_meta[$name] = $content;
        }
    }
    
    protected function show()
    {
    	$this->page = $this->page == "" ? View::factory(self::SKELETON):$this->page;
        $this->page->set('title', $this->title);
        $this->page->set('meta', $this->meta."\r\n");
        $this->page->set('css', $this->css."\r\n");
        $this->page->set('pcss', $this->pcss."\r\n");
        $this->page->set('js', $this->js.$this->phpjs."\r\n");
        $this->page->set('meta', $this->meta."\r\n");
        $this->page->set('header', $this->header."\r\n");
        $this->page->set('content', $this->content."\r\n");
        $this->page->set('footer', $this->footer."\r\n");
        $this->shown = true;
		$this->response->body($this->page);
    }
    
    private function compileCSS()
    {
        $ret = "";
        foreach($this->_css as $file)
        {
            $full_path = preg_match('/^http/', $file) > 0 ? $file:self::ASSET_PATH."css/".$file.".css";
            $ret .= "\t".HTML::style($full_path, array('media' => 'screen'))."\r\n";
        }
        return $ret;
    }
    
    private function compilePrintCSS()
    {
        $ret = "";
        foreach($this->_pcss as $file)
        {
            $full_path = preg_match('/^http/', $file) > 0 ? $file:self::ASSET_PATH."css/".$file.".css";
            $ret .= "\t".HTML::style($full_path, array('media' => 'print'))."\r\n";
        }
        return $ret;
    }
    
    private function compileJS()
    {
        $ret = "";
        foreach($this->_js as $file)
        {
            $full_path = preg_match('/^http/', $file) > 0 ? $file:self::ASSET_PATH."js/".$file.".js";
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
    
    private function compileMeta()
    {
        $ret = "";
        foreach($this->_meta as $k => $v)
        {
            $ret .="\t<meta name=\"$k\" content=\"$v\" />\r\n";
        }
        return $ret;
    }
    
    public function after()
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
} // End Main