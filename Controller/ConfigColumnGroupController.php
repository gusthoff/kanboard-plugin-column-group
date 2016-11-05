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

        $this->response->html($this->helper->layout->config('ColumnGroup:config_column_group/index', array(
            'columns' => $columns,
            'title' => t('Column groups').' &gt; '.t('List'),
        )));
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
        $this->response->html($this->template->render('ColumnGroup:config_column_group/create', array(
            'values' => $values,
            'errors' => $errors
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
            $result = $this->columnGroupModel->create(
                $values['code'],
                $values['title'],
                $values['description']
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

        # Add new column code for renaming
        $column['new_code'] = $column['code'];

        $this->response->html($this->helper->layout->config('ColumnGroup:config_column_group/edit', array(
            'errors' => $errors,
            'values' => $values ?: $column,
            'column' => $column,
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
            $result = $this->columnGroupModel->update(
                $values['code'],
                $values['title'],
                $values['description']
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
