<?php
/*
PHP REST SQL: A HTTP REST interface to relational databases
written in PHP

mysqli.php :: MySQLi database adapter
Copyright (C) 2004 Paul James <paul@peej.co.uk>
Copyright (C) 2012 Florian Knodt <adlerweb@adlerweb.info>

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

/**
 * PHP REST MySQLi class
 * MySQLi connection class.
 */
class mysql2 {

    /**
     * Database handle
     */
    private $db;
    
    /**
     * Connect to the database.
     * @param str[] config
     */
    public function connect($config) {
        $this->db = new mysqli(
			    $config['server'],
			    $config['username'],
			    $config['password'],
			    $config['database']
		);
		if(!$this->db) return false;
		return $this->db->set_charset($config['charset']);
    }

    /**
     * Close the database connection.
     */
    function close() {
        $this->db->close();
    }
    
    /**
     * Use a database
     */
    function select_db($database) {
        return $this->db->select_db($database);
    }
    
    /**
     * Get the columns in a table.
     * @param str table
     * @return resource A resultset resource
     */
    function getColumns($table) {
        return $this->db->query(sprintf('SHOW COLUMNS FROM %s', $table));
    }
    
    /**
     * Get a row from a table.
     * @param str table
     * @param str where
     * @return resource A resultset resource
     */
    function getRow($table, $where) {
        return $this->db->query(sprintf('SELECT * FROM %s WHERE %s', $table, $where));
    }
    
    /**
     * Get the rows in a table.
     * @param str primary The names of the primary columns to return
     * @param str table
     * @return resource A resultset resource
     */
    function getTable($primary, $table, $sort) {
        if($sort) {
            $sort = 'ORDER BY '.$sort;
        }else{
            $sort = '';
        }
        return $this->db->query(sprintf('SELECT %s FROM %s %s', $primary, $table, $sort));
    }

    /**
     * Get the tables in a database.
     * @return resource A resultset resource
     */
    function getDatabase() {
        return $this->db->query('SHOW TABLES');
    }

    /**
     * Get the primary keys for the request table.
     * @return str[] The primary key field names
     */
    function getPrimaryKeys($table) {
        $resource = $this->getColumns($table);
        $primary = NULL;
        if ($resource) {
            while ($row = $this->row($resource)) {
                if ($row['Key'] == 'PRI') {
                    $primary[] = $row['Field'];
                }
            }
        }
        return $primary;
    }
    
    /**
     * Update a row.
     * @param str table
     * @param str values
     * @param str where
     * @return bool
     */
    function updateRow($table, $values, $where) {
        return $this->db->query(sprintf('UPDATE %s SET %s WHERE %s', $table, $values, $where));
    }
    
    /**
     * Insert a new row.
     * @param str table
     * @param str names
     * @param str values
     * @return bool
     */
    function insertRow($table, $names, $values) {
        return $this->db->query(sprintf('INSERT INTO %s (`%s`) VALUES ("%s")', $table, $names, $values));
    }
    
    /**
     * Get the columns in a table.
     * @param str table
     * @return resource A resultset resource
     */
    function deleteRow($table, $where) {
        return $this->db->query(sprintf('DELETE FROM %s WHERE %s', $table, $where));
    }
    
    /**
     * Escape a string to be part of the database query.
     * @param str string The string to escape
     * @return str The escaped string
     */
    function escape($string) {
        return $this->db->real_escape_string($string);
    }
    
    /**
     * Fetch a row from a query resultset.
     * @param resource resource A resultset resource
     * @return str[] An array of the fields and values from the next row in the resultset
     */
    function row($resource) {
        return $resource->fetch_assoc();
    }

    /**
     * The number of rows in a resultset.
     * @param resource resource A resultset resource
     * @return int The number of rows
     */
    function numRows($resource) {
        return $resource->num_rows;
    }

    /**
     * The number of rows affected by a query.
     * @return int The number of rows
     */
    function numAffected() {
        return $this->db->affected_rows();
    }
    
    /**
     * Get the ID of the last inserted record. 
     * @return int The last insert ID
     */
    function lastInsertId() {
        return $this->db->insert_id();
    }
    
}
?>
