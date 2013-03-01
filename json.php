<?php

/**
 * PHP REST SQL JSON renderer class
 * This class renders the REST response data as JSON.
 * Optimized for DOJO
 */
class PHPRestSQLRenderer {
   
    /**
     * @var PHPRestSQL PHPRestSQL
     */
    var $PHPRestSQL;
   
    /**
     * Constructor.
     * @param PHPRestSQL PHPRestSQL
     */
    function render($PHPRestSQL) {
        $this->PHPRestSQL = $PHPRestSQL;
        switch($PHPRestSQL->display) {
            case 'database':
                $this->database();
                break;
            case 'table':
                $this->table();
                break;
            case 'row':
                $this->row();
                break;
        }
    }
    
    /**
     * Output the top level table listing.
     */
    function database() {
        header('Content-Type: application/json');
        $tables=array();
        if (isset($this->PHPRestSQL->output['database'])) {
            foreach ($this->PHPRestSQL->output['database'] as $table) {
                $tables[] = $table['value'];
            }
        }
        echo json_encode($tables);
    }
    
    /**
     * Output the rows within a table.
     */
    function table() {
        header('Content-Type: application/json');
        $rows=array();
        if (isset($this->PHPRestSQL->output['table'])) {
            foreach ($this->PHPRestSQL->output['table'] as $row) {
                $rows[] = $row['value'];
            }
        }
        echo json_encode($rows);
    }
    
    /**
     * Output the entry in a table row.
     */
    function row() {
        header('Content-Type: application/json');
        $fields = array();
        if (isset($this->PHPRestSQL->output['row'])) {
            foreach ($this->PHPRestSQL->output['row'] as $field) {
                $fieldName = $field['field'];
                $fields[$fieldName] = $field['value'];
            }
        }
        echo json_encode($fields);
    }

}
