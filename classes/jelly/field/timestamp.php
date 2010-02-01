<?php defined('SYSPATH') or die('No direct script access.');

class Jelly_Field_Timestamp extends Jelly_Field
{
	/**
	 * @var boolean Whether or not to automatically set now() on creation
	 */
	public $auto_now_create = FALSE;
	
	/**
	 * @var boolean Whether or not to automatically set now() on update
	 */
	public $auto_now_update = FALSE;
	
	/**
	 * @var string A date formula representing the time in the database
	 */
	public $format = NULL;
	
	/**
	 * @var string A pretty format used for representing the date to users
	 */
	public $pretty_format = 'r';
	
	/**
	 * Converts the time to a UNIX timestamp
	 *
	 * @param string $value 
	 * @return void
	 * @author Jonathan Geiger
	 */
	public function set($value)
	{
		if (FALSE !== strtotime($value))
		{
			return strtotime($value);
		}
		
		return $value;
	}
	
	/**
	 * Automatically creates or updates the time and 
	 * converts it, if necessary
	 *
	 * @param string $loaded 
	 * @return void
	 * @author Jonathan Geiger
	 */
	public function save($model, $value)
	{
		if ((!$model->loaded() && $this->auto_now_create) || ($model->loaded() && $this->auto_now_update))
		{
			$value = time();
		}
		
		// Convert if necessary
		if ($this->format)
		{
			$value = date($this->format, $value);
		}
		
		return $value;
	}
}