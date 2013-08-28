<?php defined('SYSPATH') or die('No direct script access.');

class ORM extends Kohana_ORM {
    
    protected $_returnNullOnFailedGet = true;
    
    /**
 	 * Makes an attempt to call the __get for attribute trying to be retrieved. Returns null if Kohana throws an error.
     * The idea is to make overriding the __get function easier.
 	 * @author	Brent Allen
 	 * @param	string	key	the attribute of the class instance to attempt to grab
 	 * @return 	*	    returns the attribute if it can be retrieved. If not, will either throw an error or return 
     *                  null based on the value of $this->_returnNullOnFailedGet
  	*/
    public function getAttribute($key)
    {
        try
        {
            return parent::__get($key);
        }
        catch (Exception $e)
        {
            if($this->_returnNullOnFailedGet)
            {
                return null;
            }
            else
            {
                throw $e;
            }
        }
    }
    
    /**
 	 * Overrides the __get function in Kohana ORM models. Forces it to use the new getAttribute alias.
 	 * @author	Brent Allen
 	 * @param	string	key	the attribute of the class instance to attempt to grab
 	 * @return 	*	    returns the attribute if it can be retrieved, returns null otherwise.
  	*/
    public function __get($key)
    {
        return $this->getAttribute($key);
    }
    
    /**
 	 * Makes an attempt to call the __set for attribute trying to be set.
     * The idea is to make overriding the __set function easier.
 	 * @author	Brent Allen
 	 * @param	string	key	the attribute of the class instance to attempt to grab
 	 * @return 	*	    returns the attribute if it can be retrieved. If not, will either throw an error or return 
     *                  null based on the value of $this->_returnNullOnFailedGet
  	*/
    public function setAttribute($key, $val)
    {
        try
        {
            parent::__set($key, $val);
        }
        catch (Exception $e)
        {
            throw $e;
        }
    }
    
    /**
 	 * Overrides the __set function in Kohana ORM models. Forces it to use the new setAttribute alias.
 	 * @author	Brent Allen
 	 * @param	string	key	the attribute of the class instance to attempt to grab
 	 * @return 	*	    returns the attribute if it can be retrieved, returns null otherwise.
  	*/
    public function __set($key, $val)
    {
        $this->setAttribute($key, $val);
    }
    
    /**
 	 * Sanitizes data passed into it. If array is provided, an object is returned with the same keys
 	 * @author	Brent Allen
 	 * @param	*	 data    	The data to be sanitized.
     * @param   int  method     sanitation method to use (defaults to FILTER_SANITIZE_STRING)   
 	 * @return 	object/string	an object of sanitized data, or the data that was sanitized
  	*/
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
}
