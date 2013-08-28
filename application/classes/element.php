<?php
require_once("attributelist.php");

class Element
{
    private $_selfTerminate = false;
    private $_type = "div";
    private $_content = "";
    private $_attributes = null;
    private $selfTerminators = array("area", "base", "basefont", "br", "col", "frame", "hr", "img", "input", "link", "meta", "param");

    public function Element($type = "div", $content = "", $attr = null)
    {
        $this->type = $type;
        $this->content = $content;
        $this->_attributes = new AttributeList($attr);
    }

    public function clearAttributes()
    {
        $this->_attributes = new AttributeList();
    }

    public function __toString()
    {
        if($this->_selfTerminate)
        {
            if(empty($this->_content))
            {
                return "<$this->_type$this->_attributes />";
            }
            return "<$this->_type$this->_attributes value=\"$this->_content\" />";
        }

        return "<$this->_type$this->_attributes>$this->_content</$this->_type>";
    }

    public function __get($key)
    {
        switch($key)
        {
            case "type":
                return $this->_type;
                break;
            case "content":
                return $this->_content;
                break;
            case "attributes":
                return $this->_attributes;
                break;
            case "selfTerminate":
                return $this->_selfTerminate;
                break;
            default:
                throw new Exception("$key does not exist on Object type Tag.");
                break;
        }
    }

    public function __set($key, $val)
    {
        switch($key)
        {
            case "type":
                $this->_type = strtolower($val);
                if(in_array($this->_type, $this->selfTerminators))
                {
                    $this->_selfTerminate = true;
                }
                break;
            case "content":
                $this->_content = $val;
                break;
            case "selfTerminate":
                $this->_selfTerminate = (Boolean) $val;
                break;
            default:
                throw new Exception("$key does not exist on Object type Tag.");
                break;
        }
    }

}