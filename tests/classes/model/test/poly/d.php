<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Used for testing aliasing. Has no real DB equivalent.
 *
 * @package  Jelly
 */
class Model_Test_Poly_D extends Model_Test_Poly_C
{		
	public static function initialize(Jelly_Meta $meta)
	{
		// Don't call the parent. We should be able to cope with this.
		$meta->db(Kohana::config('unittest')->db_connection);
		
		$meta->table_mode(Jelly_Model::TABLE_PER_SUBCLASS);
		
		// All fields are aliased to different columns
		$meta->fields(array(
			'field_d'      => Jelly::field('string'),
		));
		
		$meta->table('test_poly_d');
	}
}