<?php defined('SYSPATH') or die('No direct script access.');
/**
 * The C(reate) R(ead) U(pdate) D(elete) Model + more.
 *
 * @link    https://gist.github.com/1646747
 * @author  Dave Widmer
 * @author  Anis uddin Ahmad <anisniit@gmail.com>
 */
class Model_Base extends Kohana_Model
{
    /**
     * @var String  The Database table name
     */
    public $table;

    /**
     * @var String  The primary key.
     */
    public $primary = 'id';

    /**
     * List of columns
     * @var array
     */
    protected $_columns;

    /**
     * Name of connection instance
     * @var String
     */
    protected $_instanceName;

    /**
     * Cache Model. No need to init here
     *
     * @var Model_Cache
     */
    protected $_cache;


    public function __construct($db = null) {
        //parent::__construct($db);
        if(is_null($db)){
            $db = defined('SUPPRESS_REQUEST')? 'test' : 'default';
        }

        $this->_db = Database::instance($db);
        $this->_instanceName = $db;

        if($this->table){
            // Load the columns of currently using table
            $this->_columns = $this->_db->list_columns($this->table);
        }
    }


    /**
     * Inserts a new row.
     *
     * @param   array   The data to insert
     * @return  array   Insert ID and Affected Rows
     */
    public function create(array $data)
    {
            $data = $this->_trimToColums($data);

            return DB::insert($this->table)
                            ->columns(array_keys($data))
                            ->values(array_values($data))
                            ->execute($this->_db);
    }

    /**
     * Gets the first record by given the field.
     *
     * @param   mixed   a field value
     * @param   string  the field name. OPTIONAL. default primary key
     * @return  Database_Result
     */
    public function read($key, $field = false)
    {
            $field = ($field)? $field : $this->primary;

            return DB::select()
                    ->from($this->table)
                    ->where($field, '=', $key)
                    ->as_object()
                    ->execute($this->_db)
                    ->current();
    }

    /**
     * Gets the records by the given conditions.
     *
     * @example     $user->readAll(array(
     *                  array('field'=>'age', 'value' => '30', 'op' => '<')
     *                  // Only field and value if op is '='
     *                  array('field'=>'is_active', 'value' => 'Y'),
     *                  // Just maintain the sequesce as- field, value, op
     *                  array('age', '30', '<')
     *              ));
     * @param  $conditions array  The conditions as 2D array
     * @param  $limit      int    The maximum items to read. null means no limit
     * @param  $offset     int
     * @param  $fields     Field list as accepted format in DB::select
     *
     * @return Database_Result
     */
    public function readAll(array $conditions = null, $limit = null, $offset = 0, $fields = '*')
    {
        $query =  DB::select($fields)
                ->from($this->table)
                ->as_object();

        if(is_array($conditions)){
            foreach($conditions as $condition){
                $condition['field'] = isset($condition['field'])? $condition['field'] : $condition[0];
                $condition['value'] = isset($condition['value'])? $condition['value'] : $condition[1];

                if(! isset($condition['op'])){
                    $condition['op'] = (isset($condition[2]))? $condition[2] : '=';
                }

                $query->where($condition['field'], $condition['op'], $condition['value']);
            }
        }

        if(! is_null($limit)){
            $query->limit(intval($limit))
                  ->offset(intval($offset));
        }

        return $query->execute($this->_db);
    }

    /**
     * Updates record(s).
     * May update multiple fields if field is not primary/unique
     *
     * @param   mixed   field value to find row
     * @param   array   Data to update
     * @param   string  The field name. default is primary key
     *
     * @return  int     Affected rows
     */
    public function update($key, array $data, $field = null)
    {
            $data = $this->_trimToColums($data);
            $field = is_null($field) ? $this->primary : $field;

            return DB::update($this->table)
                            ->where($field, '=', $key)
                            ->set($data)
                            ->execute($this->_db);
    }

    /**
     * Deletes a record from the database.
     *
     * @param   mixed   Primary key value
     * @return  int     Affected rows
     */
    public function delete($key)
    {
        return DB::delete($this->table)
                ->where($this->primary, '=', $key)
                ->execute($this->_db);
    }

    /**
     * Remove elements from data whose keys are not a column name for current table
     *
     * @param array $data
     * @return array
     */
    protected function _trimToColums($data)
    {
        return ($this->_columns)? array_intersect_key($data, $this->_columns) : $data;
    }
}
