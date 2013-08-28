<?php

class AttributeList
{

    public function AttributeList($attr = null)
    {
        if($attr)
        {
            foreach($attr as $name => $val)
            {
                $this->$name = $val;
            }
        }
    }

    public function __toString()
    {
        $str = "";
        foreach($this as $name => $val)
        {
            $str .= " $name=\"$val\"";
        }
        return $str;
    }
}