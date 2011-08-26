<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Used for testing aliasing. Has no real DB equivalent.
 *
 * @package  Jelly
 */
class Model_Test_Poly_B extends Model_Test_Poly_A
{		
	public static function initialize(Jelly_Meta $meta)
	{
		parent::initialize($meta);
		
		$meta->table_mode(Jelly_Model::TABLE_PER_SUBCLASS);
		
		// All fields are aliased to different columns
		$meta->fields(array(
			'field_b'      => Jelly::field('string'),
		));
		
		$meta->table('test_poly_b');
		$meta->children(array('test_poly_c'));
	}
}