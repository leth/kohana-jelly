<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Used for testing aliasing. Has no real DB equivalent.
 *
 * @package  Jelly
 */
class Model_Test_Poly_A extends Jelly_Model
{		
	public static function initialize(Jelly_Meta $meta)
	{
		$meta->db(Kohana::config('unittest')->db_connection);
		
		// All fields are aliased to different columns
		$meta->fields(array(
			'id'           => Jelly::field('primary'),
			'field_a'      => Jelly::field('string'),
		));
		
		$meta->table('test_poly_a');
		$meta->children(array('test_poly_b'));
	}
}