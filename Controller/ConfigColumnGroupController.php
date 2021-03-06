<?php

namespace Kanboard\Plugin\ColumnGroup\Controller;

use Kanboard\Controller\BaseController;
use Kanboard\Core\Controller\AccessForbiddenException;

/**
 * Column Group Controller
 *
 * @package  Controller
 */
class ConfigColumnGroupController extends BaseController
{
    /**
     * Display list of column groups
     *
     * @access public
     */
    public function index()
    {
        $columns = $this->columnGroupModel->getAll();

        foreach ($columns as $key => $value)
        {
            if ($columns[$key]['project_id'] != Null)
            {
                $prj = $this->projectModel->getById($columns[$key]['project_id']);
                $columns[$key]['project_name'] = $prj['name'];
            }
        }

        $this->response->html($this->helper->layout->config('ColumnGroup:config_column_group/index', array(
            'columns' => $columns,
            'title' => t('Column groups').' &gt; '.t('List'),
        )));
    }

    function getProjects()
    {
        $projects = $this->projectModel->getAll();

        $project_ids = array();
        foreach ($projects as $p)
        {
            $project_ids[$p['id']] = $p['id'];
        }

        /* Additional empty code for Null */
        $project_ids[""] = "";

        return $project_ids;
    }

    /**
     * Show form to create a new column group
     *
     * @access public
     * @param array $values
     * @param array $errors
     * @throws \Kanboard\Core\Controller\PageNotFoundException
     */
    public function create(array $values = array(), array $errors = array())
    {
        $project_ids = $this->getProjects();

        $values['project_id'] = "";

        $this->response->html($this->template->render('ColumnGroup:config_column_group/create', array(
            'values' => $values,
            'errors' => $errors,
            'project_ids' => $project_ids
        )));
    }

    /**
     * Show simple form to create a new global column group without project ID
     *
     * @access public
     * @param array $values
     * @param array $errors
     * @throws \Kanboard\Core\Controller\PageNotFoundException
     */
    public function createSimple(array $values = array(), array $errors = array())
    {
        $this->response->html($this->template->render('ColumnGroup:config_column_group/create_simple', array(
            'values' => $values,
            'errors' => $errors,
        )));
    }

    /**
     * Validate and add a new column group
     *
     * @access public
     */
    public function save()
    {
        $values = $this->request->getValues();

        $valid = True;
        $errors = Null;

        if ($valid) {

            /* Map empty code to Null */
            if ($values['project_id'] === "") {
                $values['project_id'] = Null;
            }

            $result = $this->columnGroupModel->create(
                $values['code'],
                $values['title'],
                $values['description'],
                $values['project_id']
            );

            if ($result !== false) {
                $this->flash->success(t('Column group created successfully.'));
                return $this->response->redirect($this->helper->url->to('ConfigColumnGroupController', 'index', array()), true);
            } else {
                $errors['title'] = array(t('Another column group with the same name exists in the project'));
            }
        }

        return $this->create($values, $errors);
    }

    /**
     * Display a form to edit a column group
     *
     * @access public
     * @param array $values
     * @param array $errors
     */
    public function edit(array $values = array(), array $errors = array())
    {
        $column = $this->columnGroupModel->getByCode($this->request->getStringParam('column_code'));
        $project_ids = $this->getProjects();

        # Add new column code for renaming
        $column['new_code'] = $column['code'];

        /* Map Null to empty code */
        if ($column['project_id'] === Null) {
            $column['project_id'] = "";
        }

        $this->response->html($this->helper->layout->config('ColumnGroup:config_column_group/edit', array(
            'errors' => $errors,
            'values' => $values ?: $column,
            'column' => $column,
            'project_ids' => $project_ids,
            'title' => t('Column groups').' &gt; '.t('Edit')
        )));
    }

    /**
     * Validate and update a column group
     *
     * @access public
     */
    public function update()
    {
        $values = $this->request->getValues();

        $valid = True;
        $errors = Null;

        if ($valid) {
            /* Map empty code to Null */
            if ($values['project_id'] === "") {
                $values['project_id'] = Null;
            }

            $result = $this->columnGroupModel->update(
                $values['code'],
                $values['title'],
                $values['description'],
                $values['project_id']
            );

            if ($result) {
                if ($values['new_code'] != $values['code']) {
                    $result = $this->columnGroupModel->rename(
                        $values['code'],
                        $values['new_code']
                    );
                }

                $this->flash->success(t('Board updated successfully.'));
                return $this->response->redirect($this->helper->url->to('ConfigColumnGroupController', 'index', array(
                    'plugin' => 'ColumnGroup',
                    'title' => t('Column groups').' &gt; '.t('List'),
                )));
            } else {
                $this->flash->failure(t('Unable to update this board.'));
                return $this->response->redirect($this->helper->url->to('ConfigColumnGroupController', 'index', array(
                    'plugin' => 'ColumnGroup',
                    'title' => t('Column groups').' &gt; '.t('List'),
                )));
            }
        }
    }

    /**
     * Confirm column group suppression
     *
     * @access public
     */
    public function confirm()
    {
        $column_code = $this->request->getStringParam('column_code');
        $column = $this->columnGroupModel->getByCode($this->request->getStringParam('column_code'));

        $this->response->html($this->helper->layout->config('ColumnGroup:config_column_group/remove', array(
            'column_code' => $column_code,
            'title' => t('Column groups').' &gt; '.t('Confirm')
        )));
    }

    /**
     * Remove a column group
     *
     * @access public
     */
    public function remove()
    {
        $this->checkCSRFParam();
        $column_code = $this->request->getStringParam('column_code');

        $result = $this->columnGroupModel->remove($column_code);

        if ($result) {
            $this->flash->success(t('Column removed successfully.'));
        } else {
            $this->flash->failure(t('Unable to remove this column.'));
        }

        $this->response->redirect($this->helper->url->to('ConfigColumnGroupController', 'index', array(
            'plugin' => 'ColumnGroup',
            'title' => t('Column groups').' &gt; '.t('List'),
        )));
    }
}
