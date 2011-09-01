<?php defined('SYSPATH') or die('No direct script access.');

class Kohana_Unittest_Jelly_TestCase extends Kohana_Unittest_Database_TestCase {

	public function __construct($name = NULL, array $data = array(), $dataName = '')
	{
		parent::__construct($name,$data, $dataName);

		$this->_database_connection = Kohana::$config->load('jelly_test')->db_connection;
	}

	/**
	* Creates tables.
	 *
	 * @return  void
	 * @uses    parent::setUpBeforeClass
	 * @uses    Kohana::find_file
	 * @uses    DB::query
	*/
	public static function setUpBeforeClass()
	{
		parent::setUpBeforeClass();

		// Set database name
		$db_name = Kohana::$config->load('jelly_test')->db_connection;

		// Load config
		$config = Kohana::$config->load('database')->$db_name;

		// Set type

		$type = $config['type'];

		if ($type == 'pdo')
		{
			// Get type from config
			$type = explode(':', $config['connection']['dsn']);
			$type = $type[0];
		}

		// Find file
		$file = Kohana::find_file('tests/test_data/jelly', 'test-schema-'.$type, 'sql');

		// Get contents
		$file = file_get_contents($file);

		// Extract queries
		$queries = explode(';', $file);

		foreach ($queries as $query)
		{
			if (empty($query))
			{
				// Don't run empty queries
				continue;
			}

			// Execute query
			DB::query(NULL, $query)->execute($db_name);
		}
	}

	/**
	 * Creates the database connection.
	 *
     * @return  PHPUnit_Extensions_Database_DB_IDatabaseConnection
	 * @uses    Kohana::config
	 * @uses    Arr::get
	 * @uses    PDO
     */
    public function getConnection()
    {
		// Set database name
		$db_name = Kohana::$config->load('jelly_test')->db_connection;

		// Load config
		$config = Kohana::$config->load('database')->{$this->_database_connection};

		// Create database instance
		$db = Database::instance($db_name);

		if ($db instanceof Database_PDO)
		{
			// With in-memory databases we can't reconnect to the database, because it'll create a new one
			$db->connect();

			// Set PDO
			$pdo = $db->connection();
		}
		else
		{
			// Use MySQL connection
			$dsn = Arr::path($config, 'connection.dsn', $config['type'].':host='.$config['connection']['hostname'].';dbname='.$config['connection']['database']);
			$pdo = new PDO($dsn, $config['connection']['username'], $config['connection']['password']);
		}

		// Create connection
		// IMPORTANT: database has to be set in config, even for PDO
		return $this->conn = $this->createDefaultDBConnection($pdo, $config['connection']['database']);
    }

    /**
	 * Inserts default data into database.
	 *
     * @return  PHPUnit_Extensions_Database_DataSet_IDataSet
	 * @uses    Kohana::find_file
     */
    public function getDataSet()
    {
		return $this->createXMLDataSet(Kohana::find_file('tests/test_data/jelly', 'test', 'xml'));
    }

} // End Kohana_Unittest_Jelly_TestCase