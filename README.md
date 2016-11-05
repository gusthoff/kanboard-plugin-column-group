Column Group (Plug-in for Kanboard)
===============================================
Version 0.1.1

[![Build Status](https://travis-ci.org/gusthoff/kanboard-plugin-column-group.svg?branch=master)](https://travis-ci.org/gusthoff/kanboard-plugin-column-group)


1. Introduction
---------------

This is a plug-in for [Kanboard](https://kanboard.net), the Kanban Project 
Management Software. It allows for managing column groups.


2. License & Copyright
----------------------

This plug-in is available "as is" under MIT License. Unless stated otherwise,
the copyright is held by Gustavo A. Hoffmann.

This plug-in is based on source-code from 
[Kanboard](https://github.com/kanboard/kanboard) itself (copyright by 
[Frédéric Guillot](https://github.com/fguillot)).


3. Features
-----------

This plug-in extends Kanboard's functionality with the following features:

- Allows for adding and managing global and project-specific column groups.
- Allows for assigning columns from each project to column groups.
    - Multiple columns from the same project can be assigned to a single column
      group.
    - Multiple columns from various projects can share column groups.
    - Projects can use both global and local column groups.
- Displays column groups above the corresponding columns on the project board.
    - Adjacent columns belonging to the same column group can be easily
      identified on the board.
- Allows for searching for column groups using this filter: `column_groups`.
    - This also allows for searching for tasks from various projects sharing
      the same column group.


4. Known Limitations
--------------------

This is an alpha release of the plug-in. These are some of the known
limitations:

- It is only possible to reference column groups by the corresponding column
  group code.

- GUI for column groups (on the project board) is very simple.


5. Testing
----------

This plug-in was manually tested with the following configuration:

- Kanboard 1.0.34
- PHP 7.0.8
- Postgres 9.5.5
- Sqlite 3.14.1
- Mysql 5.7.16


6. Requirements
---------------

- Kanboard >= 1.0.34
- PHP >= 5.3.3


7. Installation
---------------

You have the choice between 3 methods:

1. Install the plug-in from the Kanboard plug-in manager in one click
2. Download the zip file and decompress everything under the directory 
   `plugins/ColumnGroup`
3. Clone this repository into the folder `plugins/ColumnGroup`

Note: The plug-in folder is case-sensitive.

