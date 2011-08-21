<?php
/**
 * Tests for Jelly_Model functionality.
 *
 * @package Jelly
 * @group   jelly
 * @group   jelly.polymorphic
 * @group   jelly.polymorphic.model
 */
class Jelly_PolymorphicModelTest extends Unittest_Jelly_TestCase
{
	public function provider_test_fields()
	{
		return array(
			array('Test_Poly_A', array('id', 'field_a')),
			array('Test_Poly_B', array('id', 'field_a', 'field_b')),
			array('Test_Poly_D', array('id', 'field_a', 'field_b', 'field_c', 'field_d')),
		);
	}
	
	/**
	 * Test that fields are inherited
	 * 
	 * @dataProvider provider_test_fields
	 */
	public function test_fields($model_name, $expected_fields)
	{
		$model = Jelly::factory($model_name);
		
		$expected_fields = array_combine($expected_fields,$expected_fields);
		$found_fields = $model->meta()->fields();

		foreach ($found_fields as $name => $field)
			$this->assertArrayHasKey($name, $expected_fields);

		foreach ($expected_fields as $name)
			$this->assertArrayHasKey($name, $found_fields);
	}
	
	public function provider_test_load()
	{
		return array(
			array('Test_Poly_A', array(
				'id'      =>  1,
				'field_a' => '1 field_a'
			)),
			array('Test_Poly_B', array(
				'id'      =>  2,
				'field_a' => '2 field_a',
				'field_b' => '2 field_b',
			)),
			array('Test_Poly_D', array(
				'id'      =>  3,
				'field_a' => '3 field_a',
				'field_b' => '3 field_b',
				'field_c' => '3 field_c',
				'field_d' => '3 field_d',
			)),
		);
	}
	
	/**
	 * Test loading a single instance by Primary Key
	 * 
	 * @dataProvider provider_test_load
	 */
	public function test_load_single($model_name, $expected_values)
	{
		$model = Jelly::query($model_name, $expected_values['id'])
			->select();

		foreach ($expected_values as $key => $value)
		{
			$this->assertEquals($value, $model->$key);
		}
	}
	
	public function provider_test_load_tree()
	{
		$models = self::provider_test_load();
		
		return array(
			array('Test_Poly_A', array( &$models[0], &$models[1], &$models[2])),
			array('Test_Poly_B', array( &$models[1], &$models[2])),
			// TODO consider how to select abstract table-per-concrete models
			// array('Test_Poly_C', &$models[2]),
			array('Test_Poly_D', array( &$models[2])),
		);
	}
	
	/**
	 * Test queries for one class return instances of subclasses
	 * NB: Currently fails
	 * @dataProvider provider_test_load_tree
	 */
	public function test_load_tree($model_name, $expected_models)
	{
		$models = Jelly::query($model_name)
			->order_by('id')
			->select();
		
		$this->assertEquals(count($expected_models), count($models));
		for ($i=0; $i < count($expected_models); $i++)
		{
			$model = $models[$i];
			list($exp_model, $expected_values) = $expected_models[$i];
			
			$this->assertInstanceOf(Jelly::class_name($exp_model), $model);
			
			foreach ($expected_values as $key => $value)
			{
				$this->assertEquals($value, $model->$key);
			}
		}
	}
}