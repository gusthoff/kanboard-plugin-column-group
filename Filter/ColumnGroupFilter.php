<?php

namespace Kanboard\Plugin\ColumnGroup\Filter;

use Kanboard\Core\Filter\FilterInterface;
use Kanboard\Filter\TaskColumnFilter;
use Kanboard\Model\ColumnModel;
use Kanboard\Model\TaskModel;

/**
 * Filter tasks by column
 *
 * @package filter
 */
class ColumnGroupFilter extends TaskColumnFilter implements FilterInterface
{
    /**
     * Get search attribute
     *
     * @access public
     * @return string[]
     */
    public function getAttributes()
    {
        return array('column_groups');
    }

    /**
     * Apply filter
     *
     * @access public
     * @return FilterInterface
     */
    public function apply()
    {
        $this->query->eq(ColumnModel::TABLE.'.column_group_code', $this->value);

        return $this;
    }
}
