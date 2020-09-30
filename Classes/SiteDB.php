<?php
namespace Classes;
class SiteDB
{
	/** @var */
	private $pdo;

	/**
	 * Constructor
	 *
	 * @param string $host The database host name.
	 * @param string $dbname The database name.
	 * @param string $username The database username.
	 * @param string $password The database password.
	 */
	public function __construct($host, $dbname, $username, $password)
	{
		$pdo = new \PDO('mysql:host='.$host.';dbname='.$dbname.';charset=utf8', $username, $password);
		$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		$this->pdo = $pdo;
	}

	/**
	 * A function to query the database.
	 *
	 * @param string $query The query string.
	 * @param array $params The parameters that
	 * @return array The data returned from the query.
	 */
	public function query($query, $params = array()) : ?array
	{
		$statement = $this->pdo->prepare($query);
		$statement->execute($params);

		if (explode(' ', $query)[0] == 'SELECT') {
			$data = $statement->fetchAll();
			return $data;
		}
	}
}
