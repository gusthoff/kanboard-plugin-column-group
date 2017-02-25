<?php

namespace Kanboard\Plugin\ColumnGroup;

use Kanboard\Core\Plugin\Base;
use Kanboard\Core\Translator;
use Kanboard\Model\TaskModel;
use Kanboard\Plugin\ColumnGroup\Filter\ColumnGroupFilter;
use PicoDb\Table;

class Plugin extends Base
{
    public function initialize()
    {
        $container = $this->container;
        
        $this->template->hook->attach('template:project:sidebar', 'ColumnGroup:project/sidebar');
        $this->template->hook->attach('template:config:sidebar', 'ColumnGroup:config/sidebar');
        $this->template->setTemplateOverride('board/table_column', 'ColumnGroup:board/table_column');

        $this->template->hook->attach('template:board:table:column:before-header-row', 'ColumnGroup:board/table_column_groups');

        $this->container->extend('taskLexer', function($taskLexer, $c) {
            $taskLexer->withFilter(new ColumnGroupFilter());
            return $taskLexer;
        });
    }

    public function onStartup()
    {
        Translator::load($this->languageModel->getCurrentLanguage(), __DIR__.'/Locale');
    }

    public function getClasses()
    {
        return array(
            'Plugin\ColumnGroup\Model' => array(
                'ColumnGroupModel'
            )
        );
    }

    public function getPluginName()
    {
        return 'ColumnGroup';
    }

    public function getPluginDescription()
    {
        return t('Add column groups for columns in project');
    }

    public function getPluginAuthor()
    {
        return 'Gustavo A. Hoffmann';
    }

    public function getPluginVersion()
    {
        return '0.1.0';
    }

    public function getPluginHomepage()
    {
        return 'https://github.com/gusthoff/kanboard-plugin-column-group';
    }
}
