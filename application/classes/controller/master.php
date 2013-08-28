<?php defined('SYSPATH') or die('No direct script access.');

abstract class Controller_Master extends Controller {

    const ASSET_PATH = "assets/";
    const SKELETON = "templates/skeleton";
    const DEFAULT_NAV = "templates/nav";
    const DEFAULT_FOOTER = "templates/footer";
    protected $page = "";
    private $_css = array("reset",  "screen");
    private $_pcss = array();
    private $_js = array("global");
    private $_phpjs = array();
    private $_meta = array("author" => "B.W. Allen", "description" => "", "viewport" => "width=device-width,initial-scale=1");
    protected $content = "";
    protected $nav = "";
    protected $footer = "";
    protected $title = "Default Title";
    protected $shown = false;
    protected $ajax = false;
    protected $params;

    public function Controller_Master($request, $response)
    {
        parent::__construct($request, $response);
        $this->nav = $this->nav == "" ? View::factory(self::DEFAULT_NAV):$this->nav;
        $this->footer = $this->footer == "" ? View::factory(self::DEFAULT_FOOTER):$this->footer;
    }
    
    public function before()
    {
        $this->params = (Object) $this->request->param();
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
        $this->page->set('nav', $this->nav."\r\n");
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