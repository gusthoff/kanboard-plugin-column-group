<?php

namespace Kanboard\Plugin\ColumnGroup\Model;

use Kanboard\Core\Base;
use Kanboard\Model\ColumnModel;

/**
 * Column Group Model
 *
 * @package  ColumnGroupModel
 */
class ColumnGroupModel extends Base
{
    /**
     * SQL table name
     *
     * @var string
     */
    const TABLE = 'column_groups';

    /**
     * Get a column by the id
     *
     * @access public
     * @param  string  $column_code Column code
     * @return array
     */
    public function getByCode($column_code)
    {
        return $this->db->table(self::TABLE)->eq('code', $column_code)->findOne();
    }

    /**
     * Get a column code by the name
     *
     * @access public
     * @param  string   $title
     * @return integer
     */
    public function getColumnCodeByTitle($title)
    {
        return (int) $this->db->table(self::TABLE)->eq('title', $title)->findOneColumn('code');
    }

    /**
     * Get a column title by the code
     *
     * @access public
     * @param  string  $column_code Column code
     * @return integer
     */
    public function getColumnTitleByCode($column_code)
    {
        return $this->db->table(self::TABLE)->eq('code', $column_code)->findOneColumn('title');
    }

    /**
     * Get all columns sorted by position for a given project
     *
     * @access public
     * @return array
     */
    public function getAll()
    {
        return $this->db->table(self::TABLE)->findAll();
    }

    /**
     * Get a column group code by the column id (of an individual column)
     *
     * @access public
     * @param  string  $column_id  Column ID
     * @return string
     */
    public function getColumnGroupByColumn($column_id)
    {
        return $this->db
                    ->table(ColumnModel::TABLE)
                    ->eq(ColumnModel::TABLE.'.id', $column_id)
                    ->findOneColumn('column_group_code');
    }

    /**
     * Get the list of column groups
     *
     * @access public
     * @param  boolean  $prepend      Prepend a default value
     * @return array
     */
    public function getList($prepend = false)
    {
        $listing = $this->db->hashtable(self::TABLE)->getAll('code', 'title');
        return $prepend ? array(-1 => t('All columns')) + $listing : $listing;
    }

    /**
     * Add a new column group to the column groups
     *
     * @access public
     * @param  string  $column_code Column code
     * @param  string  $title       Column title
     * @param  string  $description Column description
     * @return bool|int
     */
    public function create($column_code, $title, $description = '')
    {
        $values = array(
            'code' => $column_code,
            'title' => $title,
            'description' => $description,
        );

        return $this->db->table(self::TABLE)->persist($values);
    }

    /**
     * Update a project column
     *
     * @access public
     * @param  string    $column_id     Column ID
     * @param  string    $column_code   Column code
     * @return boolean
     */
    public function updateColumn($column_id, $column_code)
    {
        echo $column_id . " -- " . $column_code ." --\n";
        return $this->db->table(ColumnModel::TABLE)->eq('id', $column_id)->update(array(
            'column_group_code' => $column_code,
        ));
    }

    /**
     * Update a column group
     *
     * @access public
     * @param  string    $column_code   Column code
     * @param  string    $title         Column title
     * @param  string    $description   Optional description
     * @return boolean
     */
    public function update($column_code, $title, $description = '')
    {
        return $this->db->table(self::TABLE)->eq('code', $column_code)->update(array(
            'title' => $title,
            'description' => $description,
        ));
    }

    /**
     * Rename code of a column group
     *
     * @access public
     * @param  string    $column_code       Column code
     * @param  string    $new_column_code   New Column code
     * @return boolean
     */
    public function rename($column_code, $new_column_code)
    {
        # Get data for original column group
        $c = $this->getByCode($column_code);
        
        # Add new column group with renamed code using original data
        $result = $this->create($new_column_code, $c['title'], $c['description']);

        # Updated all columns with "new" column group
        $result = $this->db->table(ColumnModel::TABLE)->eq('column_group_code', $column_code)->update(array(
            'column_group_code' => $new_column_code,
        ));
        
        if ($result) {
            # Remove old column group
            $result = $this->remove($column_code);
        }
        return $result;
    }

    /**
     * Remove a column group
     *
     * @access public
     * @param  string    $column_code   Column code
     * @return boolean
     */
    public function remove($column_code)
    {
        # Remove column group from all columns
        $result = $this->db->table(ColumnModel::TABLE)->eq('column_group_code', $column_code)->update(array(
            'column_group_code' => Null,
        ));

        if ($result) {
            $result = $this->db->table(self::TABLE)->eq('code', $column_code)->remove();
        }
        return $result;
    }
}
