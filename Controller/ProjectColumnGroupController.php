<?php

namespace Kanboard\Plugin\ColumnGroup\Controller;

use Kanboard\Controller\BaseController;
use Kanboard\Core\Controller\AccessForbiddenException;

/**
 * Column Group Controller
 *
 * @package  Controller
 */
class ProjectColumnGroupController extends BaseController
{
    /**
     * Display column group list
     *
     * @access public
     */
    public function index()
    {
        $project = $this->getProject();
        $columns = $this->columnModel->getAll($project['id']);

        $this->response->html($this->helper->layout->project('ColumnGroup:project_column_group/index', array(
            'columns' => $columns,
            'project' => $project,
            'title' => t('Edit column groups')
        )));
    }

    /**
     * Display a form to edit a column
     *
     * @access public
     * @param array $values
     * @param array $errors
     */
    public function edit(array $values = array(), array $errors = array())
    {
        $project = $this->getProject();
        $column = $this->columnModel->getById($this->request->getIntegerParam('column_id'));
        $column_groups = $this->columnGroupModel->getAll();
        
        $column_group_codes = array();
        foreach ($column_groups as $c)
        {
            $column_group_codes[$c['code']] = $c['code'];
        }

        /* Additional empty code for Null */
        $column_group_codes[""] = "";

        /* Map Null to empty code */
        if ($column['column_group_code'] === Null) {
            $column['column_group_code'] = "";
        }

        $this->response->html($this->helper->layout->project('ColumnGroup:project_column_group/edit', array(
            'errors' => $errors,
            'values' => $values ?: $column,
            'project' => $project,
            'column' => $column,
            'column_group_codes' => $column_group_codes,
        )));
    }

    /**
     * Validate and update a column with a column group
     *
     * @access public
     */
    public function update()
    {
        $project = $this->getProject();
        $values = $this->request->getValues();

        $valid = True;
        $errors = Null;

        if ($valid) {
            $id                 = $values['id'];
            $column_group_code  = $values['column_group_code'];

            /* Map empty code to Null */
            if ($column_group_code === "") {
                $column_group_code = Null;
            }

            $result = $this->columnGroupModel->updateColumn(
                $id,
                $column_group_code
            );

            if ($result) {
                $this->flash->success(t('Board updated successfully.'));
                return $this->response->redirect($this->helper->url->to('ProjectColumnGroupController', 'index', array('plugin' => 'ColumnGroup', 'project_id' => $project['id'])));
            } else {
                $this->flash->failure(t('Unable to update this board.'));
            }
        }

        return $this->edit($values, $errors);
    }

    /**
     * Confirm column group suppression
     *
     * @access public
     */
    public function confirm()
    {
        $project = $this->getProject();
        $column_id = $this->request->getIntegerParam('column_id');

        $this->response->html($this->helper->layout->project('ColumnGroup:project_column_group/remove', array(
            'column_id' => $column_id,
            'column_group_code' => $this->columnGroupModel->getColumnGroupByColumn($column_id),
            'project' => $project,
        )));
    }

    /**
     * Remove association to column group for a column
     *
     * @access public
     */
    public function remove()
    {
        $project = $this->getProject();
        $this->checkCSRFParam();

        $column_id = $this->request->getIntegerParam('column_id');
        $result = $this->columnGroupModel->updateColumn($column_id, Null);

        if ($result) {
            $this->flash->success(t('Column group removed successfully.'));
        } else {
            $this->flash->failure(t('Unable to remove this column group.'));
        }

        $this->response->redirect($this->helper->url->to('ProjectColumnGroupController', 'index', array(
            'plugin' => 'ColumnGroup', 
            'project_id' => $project['id']
        )));
    }
}
