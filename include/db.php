<?php
declare(strict_types=1);
namespace DB;

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);


/**
 * Represents the connection with the database, may only be instantiated once.
 */
class Database
{
	public \mysqli $conn;
	

	public function __construct (string $hostname, string $username, string $password, string $database)
	{
		$this->conn = new \mysqli($hostname, $username, $password, $database) or die("Failed to stablish a connection to the database");
	}


	public function exec (Stmt $statement)
	{
		try {
			$result = mysqli_query($this->conn, "$statement");

			return $result;
		}
		catch (\mysqli_sql_exception $th) {
			var_dump($th);
		}
	}
}

$db = new Database("localhost", "academoapp_root", "5ayC7ssuJ^K@%!^m", "academoapp_shareyourcanvas");


/**
 * Represents a MySQL statement.
 */
class Stmt
{
	private string $query;


	public function __construct (string $query)
	{
		$this->query = $query;
	}


	public function __toString()
	{
		return $this->query;
	}
}

/**
 * Builds a SELECT statement and returns it as a Stmt object.
 * @param array $fields A list of the fields to retrieve, or * to get all.
 * @param string $table The table to retrieve data from.
 * @param ?array $constraints An optional array of QueryConstraint to filter the result.
 */
function select (array $fields, string $table, ?array $constraints): Stmt
{
	$query = "SELECT " . join(", ", $fields) . " FROM `$table`";

	if (!is_null($constraints) && count($constraints) > 0) {
		$len = count($constraints);

		for ($i = 0; $i < $len; $i++)
		{
			$qc = $constraints[$i];

			if (!($qc instanceof QueryConstraint))
				throw new \Error("Invalid query constraint");

			if (strpos("$qc", "LIMIT") && strrpos($query, "AND")) {
				$query = substr($query, 0, strrpos($query, "AND", -1));
				$query .= $qc;	
			}
			else {
				$query .= $qc;
				$query .= ($i != ($len - 1)) ? " AND " : "";
			}
		}
	}

	return new Stmt($query);
}

/**
 * Builds an INSERT INTO statement and returns it as a Stmt object.
 * @param string $table The table to insert the new register on.
 * @param object|array $data An object or associative array containing the data to insert.
 * The keys of said object/array are considered as the fields of the table, while the
 * values are the data to insert.
 */
function insert_into (string $table, object|array $data): Stmt
{
	$query = "INSERT INTO `$table` (";

	if (is_object($data))
		$data = (array)$data;

	$keys = array_keys($data);
	$len = count($keys);

	for ($i = 0; $i < $len; $i++)
	{
		$query .= "`$keys[$i]`";
		$query .= ($i != ($len - 1)) ? ", " : "";
	}

	$query .= ") VALUES (";
	$vals = array_values($data);
	$len = count($vals);

	for ($i = 0; $i < $len; $i++)
	{
		if (is_null($vals[$i]))	$query .= "NULL";
		elseif (is_numeric($vals[$i])) $query .= "$vals[$i]";
		else $query .= "'$vals[$i]'";

		$query .= ($i != ($len - 1)) ? ", " : "";
	}

	$query .= ")";

	return new Stmt($query);
}

/**
 * Builds an UPDATE statement and returns it as a Stmt object.
 * @param string $table The table to update the registers from.
 * @param object|array $data The data to update the registers with. The keys of said
 * object/array are considered as the fields of the table, while the values are the data
 * to update.
 * @param ?array $constraints An optional array of QueryConstraint to filter the registers to update.
 */
function update (string $table, object|array $data, ?array $constraints): Stmt
{
	$query = "UPDATE `$table` SET ";

	if (is_object($data))
		$data = (array)$data;

	foreach ($data as $key => $val) {
		$query .= "`$key` = ";

		if (is_null($val))	$query .= "NULL, ";
		elseif (is_numeric($val)) $query .= "$val, ";
		else $query .= "'$val', ";
	}

	$query = substr($query, 0, strrpos($query, ", ", -1)) . " ";

	if (!is_null($constraints))
	{
		$len = count($constraints);

		for ($i = 0; $i < $len; $i++)
		{
			$qc = $constraints[$i];

			if (!($qc instanceof QueryConstraint))
				throw new \Error("Invalid query constraint");

			if (strpos("$qc", "LIMIT") && strrpos($query, "AND")) {
				$query = substr($query, 0, strrpos($query, "AND", -1));
				$query .= $qc;	
			}
			else {
				$query .= $qc;
				$query .= ($i != ($len - 1)) ? " AND " : "";
			}
		}
	}

	return new Stmt($query);
}

/**
 * Builds a DELETE statement and returns it as a Stmt object.
 * @param string $table The table to delete registers from.
 * @param ?array $constraints An optional array of QueryConstraint to filter the registers to delete.
 */
function delete (string $table, ?array $constraints): Stmt
{
	$query = "DELETE FROM `$table`";

	if (!is_null($constraints))
	{
		$len = count($constraints);

		for ($i = 0; $i < $len; $i++)
		{
			$qc = $constraints[$i];

			if (!($qc instanceof QueryConstraint))
				throw new \Error("Invalid query constraint");

			if (strpos("$qc", "LIMIT") && strrpos($query, "AND")) {
				$query = substr($query, 0, strrpos($query, "AND", -1));
				$query .= $qc;	
			}
			else {
				$query .= $qc;
				$query .= ($i != ($len - 1)) ? " AND " : "";
			}
		}
	}

	return new Stmt($query);
}


/**
 * Represents a constraint in a MySQL statement, such as WHERE, ORDER BY or LIMIT.
 */
class QueryConstraint
{
	private string $query;


	function __construct (string $query)
	{
		$this->query = $query;
	}


	public function __toString()
	{
		return $this->query;
	}
}

/**
 * Creates a valid WHERE constraint for use in statements.
 * @param string $field The field to make the comparison with.
 * @param string $op One of the available and compatible WHERE operators.
 * @param mixed $val The value to compare the field against.
 * @param bool $and Whether or not this constraints comes after another
 * constraint to adapt it.
 */
function where (string $field, string $op, mixed $val, bool $and = false): QueryConstraint
{
	$query = ($and ? " " : " WHERE ") . "`$field` $op ";
	
	if (is_null($val))	$query .= "NULL";
	elseif (is_numeric($val)) $query .= "$val";
	else $query .= "'$val'";
	
	return new QueryConstraint($query);
}

/**
 * Creates a valid ORDER BY constraint for use in statements.
 * @param string $field The field to order by the results.
 * @param string One of the available and compatible ORDER BY operators.
 * @param bool $and Whether or not this constraints comes after another
 * constraint to adapt it.
 */
function order_by (string $field, string $op = "ASC", bool $and = false): QueryConstraint
{
	$query = ($and ? " " : " ORDER BY ") . " `$field` $op ";

	return new QueryConstraint($query);
}


/**
 * Creates a valid LIMIT constraint for use in statements.
 * @param int $rows The amount of rows to return per query.
 * @param int $offset How many records to skip before returning data.
 * @note This constraint should be the last one of any query.
 */
function limit (int $rows, int $offset = 0): QueryConstraint
{
	$query = " LIMIT $offset, $rows";

	return new QueryConstraint($query);
}

?>