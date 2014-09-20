<?php
/*********************************************************************************
 * Orangescrum Community Edition is a web based Project Management software developed by
 * Orangescrum. Copyright (C) 2013-2014
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more
 * details.
 *
 * You should have received a copy of the GNU General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact Orangescrum, 2059 Camden Ave. #118, San Jose, CA - 95124, US. 
   or at email address support@orangescrum.com.
 *
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * Orangescrum" logo. If the display of the logo is not reasonably feasible for
 * technical reasons, the Appropriate Legal Notices must display the words
 * "Powered by Orangescrum".
 ********************************************************************************/
 
 
/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/View/Pages/home.ctp)...
 */
Router::connect('/', array('controller' => 'users', 'action' => 'login'));
Router::connect('/mydashboard', array('controller' => 'easycases', 'action' => 'mydashboard'));
Router::connect('/dashboard', array('controller' => 'easycases', 'action' => 'dashboard'));
Router::connect('/getting_started/*', array('controller' => 'users', 'action' => 'getting_started'));
Router::connect('/onbording', array('controller' => 'projects', 'action' => 'onbording'));
Router::connect('/license/*', array('controller' => 'users', 'action' => 'license'));
Router::connect('/bug-report/*', array('controller' =>'reports', 'action' => 'glide_chart'));
Router::connect('/task-report/*', array('controller' =>'reports', 'action' => 'chart'));
Router::connect('/hours-report/*', array('controller' =>'reports', 'action' => 'hours_report'));
Router::connect('/how-it-works/*', array('controller' => 'users', 'action' => 'tour'));

Router::connect('/users/notification', array('controller' => 'users', 'action' => 'email_notification'));
Router::connect('/activities', array('controller' => 'users', 'action' => 'activity'));
Router::connect('/help', array('controller' => 'easycases', 'action' => 'help'));
Router::connect('/help/*', array('controller' => 'easycases', 'action' => 'help'));

Router::connect('/reminder-settings', array('controller' => 'projects', 'action' => 'groupupdatealerts'));
Router::connect('/import-export', array('controller' => 'projects', 'action' => 'importexport'));
Router::connect('/task-type', array('controller' => 'projects', 'action' => 'task_type'));
Router::connect('/my-company', array('controller' => 'users', 'action' => 'mycompany'));
Router::connect('/milestone/saveMilestoneTitle', array('controller' => 'milestones', 'action' => 'saveMilestoneTitle'));
Router::connect('/milestone/*', array('controller' => 'milestones', 'action' => 'milestone'));

/**
 * Load all plugin routes. See the CakePlugin documentation on
 * how to customize the loading of plugin routes.
 */
	CakePlugin::routes();

/**
 * Load the CakePHP default routes. Only remove this if you do not want to use
 * the built-in default routes.
 */
	require CAKE . 'Config' . DS . 'routes.php';
