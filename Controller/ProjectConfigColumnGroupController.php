<?php

namespace Kanboard\Plugin\ColumnGroup\Controller;

use Kanboard\Controller\BaseController;
use Kanboard\Core\Controller\AccessForbiddenException;

/**
 * Column Group Controller for current project
 *
 * @package  Controller
 */
class ProjectConfigColumnGroupController extends BaseController
{
    /**
     * Display list of column groups for current project
     *
     * @access public
     */
    public function index()
    {
        $project = $this->getProject();
        $global_columns = $this->columnGroupModel->getAllGlobal();
        $columns = $this->columnGroupModel->getAllProject($project['id'], false);
        $ext_columns = $this->columnGroupModel->getAllExternalProject($project['id']);

        $this->response->html($this->helper->layout->project('ColumnGroup:project_config_column_group/index', array(
            'project' => $project,
            'columns' => $columns,
            'global_columns' => $global_columns,
            'ext_columns' => $ext_columns,
            'title' => t('Column groups').' &gt; '.t('List'),
        )));
    }

    /**
     * Show form to create a new column group for current project
     *
     * @access public
     * @param array $values
     * @param array $errors
     * @throws \Kanboard\Core\Controller\PageNotFoundException
     */
    public function create(array $values = array(), array $errors = array())
    {
        $project = $this->getProject();

        if (empty($values)) {
            $values = array('project_id' => $project['id']);
        }

        $this->response->html($this->template->render('ColumnGroup:project_config_column_group/create', array(
            'values' => $values,
            'errors' => $errors,
            'project' => $project
        )));
    }


    /**
     * Validate and add a new column group for current project
     *
     * @access public
     */
    public function save()
    {
        $values = $this->request->getValues();
        $project = $this->getProject();

        $valid = True;
        $errors = Null;

        if ($valid) {
            $result = $this->columnGroupModel->create(
                $values['code'],
                $values['title'],
                $values['description'],
                $project['id']
            );

            if ($result !== false) {
                $this->flash->success(t('Column group created successfully.'));
                return $this->response->redirect($this->helper->url->to('ProjectConfigColumnGroupController', 'index', array()), true);
            } else {
                $errors['title'] = array(t('Another column group with the same name exists in the project'));
            }
        }

        return $this->create($values, $errors);
    }

    /**
     * Display a form to edit a column group for current project
     *
     * @access public
     * @param array $values
     * @param array $errors
     */
    public function edit(array $values = array(), array $errors = array())
    {
        $project = $this->getProject();
        $column = $this->columnGroupModel->getByCode($this->request->getStringParam('column_code'));

        # Add new column code for renaming
        $column['new_code'] = $column['code'];

        $this->response->html($this->helper->layout->project('ColumnGroup:project_config_column_group/edit', array(
            'errors' => $errors,
            'values' => $values ?: $column,
            'project' => $project,
            'column' => $column,
            'title' => t('Column groups').' &gt; '.t('Edit'),
        )));
    }

    /**
     * Validate and update a column group for current project
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
            $result = $this->columnGroupModel->update(
                $values['code'],
                $values['title'],
                $values['description'],
                $project['id']
            );

            if ($result) {
                if ($values['new_code'] != $values['code']) {
                    $result = $this->columnGroupModel->rename(
                        $values['code'],
                        $values['new_code']
                    );
                }

                $this->flash->success(t('Board updated successfully.'));
                return $this->response->redirect($this->helper->url->to('ProjectConfigColumnGroupController', 'index', array(
                    'plugin' => 'ColumnGroup',
                    'title' => t('Column groups').' &gt; '.t('List'),
                    'project_id' => $project['id'],
                )));
            } else {
                $this->flash->failure(t('Unable to update this board.'));
                return $this->response->redirect($this->helper->url->to('ProjectConfigColumnGroupController', 'index', array(
                    'plugin' => 'ColumnGroup',
                    'title' => t('Column groups').' &gt; '.t('List'),
                    'project_id' => $project['id'],
                )));
            }
        }
    }

    /**
     * Confirm column group suppression for current project
     *
     * @access public
     */
    public function confirm()
    {
        $project = $this->getProject();
        $column_code = $this->request->getStringParam('column_code');
        $column = $this->columnGroupModel->getByCode($this->request->getStringParam('column_code'));

        $this->response->html($this->helper->layout->project('ColumnGroup:project_config_column_group/remove', array(
            'project' => $project,
            'column_code' => $column_code,
            'title' => t('Column groups').' &gt; '.t('Confirm'),
        )));
    }

    /**
     * Remove a column group for current project
     *
     * @access public
     */
    public function remove()
    {
        $project = $this->getProject();
        $this->checkCSRFParam();
        $column_code = $this->request->getStringParam('column_code');

        $result = $this->columnGroupModel->remove($column_code);

        if ($result) {
            $this->flash->success(t('Column removed successfully.'));
        } else {
            $this->flash->failure(t('Unable to remove this column.'));
        }

        $this->response->redirect($this->helper->url->to('ProjectConfigColumnGroupController', 'index', array(
            'project_id' => $project['id'],
            'plugin' => 'ColumnGroup',
            'title' => t('Column groups').' &gt; '.t('List'),
        )));
    }
}
