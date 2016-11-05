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
     * Get all column groups
     *
     * @access public
     * @return array
     */
    public function getAll()
    {
        return $this->db->table(self::TABLE)->findAll();
    }

    /**
     * Get all global column groups
     *
     * @access public
     * @return array
     */
    public function getAllGlobal()
    {
        $global_col_groups  = array();

        # Circumventing issue for db->eq()
        $all_col_groups = $this->db->table(self::TABLE)->findAll();

        foreach ($all_col_groups as $c) {
            if ($c['project_id'] === Null) {
                array_push($global_col_groups, $c);
            }
        }

        return $global_col_groups;
    }

    /**
     * Get all column groups for a given project
     *
     * @access public
     * @param  integer $project_id      Project ID
     * @param  Boolean $include_global  Include global column groups in query
     * @return array
     */
    public function getAllProject($project_id, $include_global = False)
    {
        $project_col_groups = $this->db->table(self::TABLE)->eq('project_id', $project_id)->findAll();

        if ($include_global) {
            $global_col_groups = $this->getAllGlobal();
            return $project_col_groups + $global_col_groups;
        }
        else {
            return $project_col_groups;
        }
    }

    /**
     * Get all external column groups for a given project
     * 
     * Does not include global column groups
     *
     * @access public
     * @param  integer $project_id      Project ID
     * @return array
     */
    public function getAllExternalProject($project_id)
    {
        return $this->db->table(self::TABLE)->neq('project_id', $project_id)->findAll();
    }

    /**
     * Get a column group code by the column id (of an individual column)
     *
     * @access public
     * @param  integer $project_id      Project ID
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
     * @param  integer $project_id  Project ID
     * @return bool|int
     */
    public function create($column_code, $title, $description = '', $project = Null)
    {
        $values = array(
            'code' => $column_code,
            'title' => $title,
            'description' => $description,
            'project_id' => $project,
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
     * @param  integer   $project_id    Project ID
     * @return boolean
     */
    public function update($column_code, $title, $description = '', $project = Null)
    {
        return $this->db->table(self::TABLE)->eq('code', $column_code)->update(array(
            'title' => $title,
            'description' => $description,
            'project_id' => $project,
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
