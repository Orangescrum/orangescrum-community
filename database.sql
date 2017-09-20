-- phpMyAdmin SQL Dump
-- version 4.1.12
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Oct 17, 2014 at 06:50 AM
-- Server version: 5.5.36
-- PHP Version: 5.4.27

--
-- Table structure for table `archives`
--

DROP TABLE IF EXISTS `archives`;
CREATE TABLE IF NOT EXISTS `archives` (
  `id` int(250) NOT NULL AUTO_INCREMENT,
  `easycase_id` int(250) NOT NULL,
  `case_file_id` int(250) NOT NULL,
  `user_id` int(250) NOT NULL,
  `type` tinyint(4) NOT NULL COMMENT '1-archive,2-move,3-delete',
  `company_id` int(11) NOT NULL,
  `dt_created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `easycase_id` (`easycase_id`),
  KEY `case_file_id` (`case_file_id`),
  KEY `user_id` (`user_id`),
  KEY `type` (`type`),
  KEY `company_id` (`company_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `case_actions`
--

DROP TABLE IF EXISTS `case_actions`;
CREATE TABLE IF NOT EXISTS `case_actions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `easycase_id` int(11) NOT NULL COMMENT 'Foreign key of "easycases"',
  `user_id` int(11) NOT NULL COMMENT 'Foreign key of "users"',
  `action` tinyint(2) NOT NULL DEFAULT '0' COMMENT '1-Close, 2-Start',
  `dt_created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `easycase_id` (`easycase_id`),
  KEY `user_id` (`user_id`),
  KEY `action` (`action`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `case_activities`
--

DROP TABLE IF EXISTS `case_activities`;
CREATE TABLE IF NOT EXISTS `case_activities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `easycase_id` int(11) NOT NULL COMMENT 'Foreign key of "easycases"',
  `comment_id` int(11) NOT NULL COMMENT 'Foreign key of "comments"',
  `case_no` smallint(6) NOT NULL,
  `project_id` int(11) NOT NULL COMMENT 'Foreign key of "projects"',
  `user_id` int(11) NOT NULL COMMENT 'Foreign key of "users"',
  `type` tinyint(4) NOT NULL COMMENT '1-New 2-Opened, 3-Closed, 4-Start, 5-Resolve, 7-Comments, 8-Deleted,10->Modified',
  `isactive` tinyint(2) NOT NULL DEFAULT '1' COMMENT '0-Inactive, 1-Active',
  `dt_created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `easycase_id` (`easycase_id`),
  KEY `comment_id` (`comment_id`),
  KEY `case_no` (`case_no`),
  KEY `project_id` (`project_id`),
  KEY `user_id` (`user_id`),
  KEY `type` (`type`),
  KEY `isactive` (`isactive`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `case_files`
--

DROP TABLE IF EXISTS `case_files`;
CREATE TABLE IF NOT EXISTS `case_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `easycase_id` int(11) NOT NULL COMMENT 'Foreign key of "easycases"',
  `comment_id` int(11) NOT NULL COMMENT 'Foreign key of "case_comments"',
  `file` varchar(222) CHARACTER SET latin1 NOT NULL,
  `thumb` varchar(222) CHARACTER SET latin1 NOT NULL,
  `file_size` decimal(7,1) NOT NULL,
  `count` smallint(6) NOT NULL,
  `downloadurl` text,
  `isactive` tinyint(2) NOT NULL DEFAULT '1' COMMENT '0-Inactive, 1-Active',
  PRIMARY KEY (`id`),
  KEY `easycase_id` (`easycase_id`),
  KEY `comment_id` (`comment_id`),
  KEY `isactive` (`isactive`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `case_file_drives`
--

DROP TABLE IF EXISTS `case_file_drives`;
CREATE TABLE IF NOT EXISTS `case_file_drives` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `easycase_id` int(11) NOT NULL COMMENT 'Foreign key of "easycases"',
  `file_info` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `case_filters`
--

DROP TABLE IF EXISTS `case_filters`;
CREATE TABLE IF NOT EXISTS `case_filters` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL COMMENT 'Foreign Key of "Users"',
  `order` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `case_recents`
--

DROP TABLE IF EXISTS `case_recents`;
CREATE TABLE IF NOT EXISTS `case_recents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `easycase_id` int(11) NOT NULL COMMENT 'Foreign key of "easycases"',
  `company_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL COMMENT 'Foreign key of "users"',
  `project_id` int(11) NOT NULL COMMENT 'Foreign key of "projects"',
  `dt_created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `case_settings`
--

DROP TABLE IF EXISTS `case_settings`;
CREATE TABLE IF NOT EXISTS `case_settings` (
  `id` int(250) NOT NULL AUTO_INCREMENT,
  `project_id` int(250) NOT NULL,
  `project_uniqid` varchar(250) NOT NULL,
  `type_id` int(250) NOT NULL,
  `assign_to` int(250) NOT NULL,
  `priority` tinyint(4) NOT NULL,
  `due_date` varchar(250) NOT NULL,
  `email` varchar(250) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `case_templates`
--

DROP TABLE IF EXISTS `case_templates`;
CREATE TABLE IF NOT EXISTS `case_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `is_active` tinyint(2) NOT NULL DEFAULT '1' COMMENT '0-Inactive, 1-Active',
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `company_id` (`company_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `case_user_emails`
--

DROP TABLE IF EXISTS `case_user_emails`;
CREATE TABLE IF NOT EXISTS `case_user_emails` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `easycase_id` int(11) NOT NULL COMMENT 'Foreign key of "easycases"',
  `user_id` int(11) NOT NULL COMMENT 'Foreign key of "users"',
  `ismail` tinyint(2) NOT NULL DEFAULT '1' COMMENT '0-Stop Mailing, 1-Email Me',
  PRIMARY KEY (`id`),
  KEY `easycase_id` (`easycase_id`,`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `case_user_views`
--

DROP TABLE IF EXISTS `case_user_views`;
CREATE TABLE IF NOT EXISTS `case_user_views` (
  `id` int(11) NOT NULL,
  `easycase_id` int(11) NOT NULL COMMENT 'Foreign key of "easycases"',
  `user_id` int(11) NOT NULL COMMENT 'Foreign key of "users"',
  `project_id` int(11) NOT NULL COMMENT 'Foreign key of "projects"',
  `istype` tinyint(2) NOT NULL DEFAULT '1' COMMENT '1-New, 2-Reply, 3-Closed, 4-Start, 5-Edit',
  `isviewed` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0-Not Viewed, 1-Viewed',
  `dt_created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

DROP TABLE IF EXISTS `companies`;
CREATE TABLE IF NOT EXISTS `companies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniq_id` text NOT NULL,
  `name` varchar(250) NOT NULL,
  `seo_url` varchar(250) NOT NULL,
  `subscription_id` int(11) NOT NULL,
  `logo` varchar(100),
  `website` varchar(100) NOT NULL,
  `contact_phone` varchar(100) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `is_beta` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1->Beta company , 0-> Default',
  `is_active` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1:Active , 2: Cancelled ',
  `is_deactivated` tinyint(2) NOT NULL DEFAULT '0' COMMENT '1-> Auto Deactivated , 2-> Deactivated by admin,3->Disable By admin,0-> Default ',
  `twitted` tinyint(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `is_active` (`is_active`),
  FULLTEXT KEY `uniq_id` (`uniq_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `company_users`
--

DROP TABLE IF EXISTS `company_users`;
CREATE TABLE IF NOT EXISTS `company_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL,
  `company_uniq_id` varchar(250) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_type` int(11) NOT NULL COMMENT '1-owner,2-Admin,3-member',
  `is_active` tinyint(2) NOT NULL DEFAULT '1' COMMENT '0-Inactive, 1-Active,2- Not confirmed , 3- Deleted user',
  `est_billing_amt` float(10,2) NOT NULL DEFAULT '0.00' COMMENT 'Keep the estimated billing amount for the period',
  `act_date` datetime DEFAULT NULL,
  `billing_start_date` datetime DEFAULT NULL,
  `billing_end_date` datetime DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `company_id` (`company_id`),
  KEY `user_id` (`user_id`),
  KEY `user_type` (`user_type`),
  KEY `is_active` (`is_active`),
  FULLTEXT KEY `company_uniq_id` (`company_uniq_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `custom_filters`
--

DROP TABLE IF EXISTS `custom_filters`;
CREATE TABLE IF NOT EXISTS `custom_filters` (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `project_uniq_id` varchar(64) NOT NULL,
  `company_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `filter_name` varchar(100) NOT NULL,
  `filter_date` text,
  `filter_duedate` datetime DEFAULT NULL,
  `filter_type_id` text NOT NULL,
  `filter_status` text NOT NULL,
  `filter_member_id` text NOT NULL,
  `filter_priority` text NOT NULL,
  `filter_assignto` text NOT NULL,
  `filter_search` text NOT NULL,
  `dt_created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `dailyupdate_notifications`
--

DROP TABLE IF EXISTS `dailyupdate_notifications`;
CREATE TABLE IF NOT EXISTS `dailyupdate_notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `dly_update` tinyint(2) NOT NULL,
  `notification_time` varchar(100) NOT NULL,
  `proj_name` varchar(200) NOT NULL,
  `mail_sent` datetime DEFAULT NULL,
  `modified` datetime NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `daily_updates`
--

DROP TABLE IF EXISTS `daily_updates`;
CREATE TABLE IF NOT EXISTS `daily_updates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `post_by` int(11) NOT NULL COMMENT 'user id : who create or update alert settings.',
  `user_id` text NOT NULL COMMENT 'user_ids are separated by comma(,)',
  `timezone_id` int(11) NOT NULL,
  `notification_time` time NOT NULL,
  `days` int(11) NOT NULL DEFAULT '5' COMMENT 'default:5',
  `cron_email_date` date DEFAULT NULL COMMENT 'When email is sent , this field is going to update',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `default_project_templates`
--

DROP TABLE IF EXISTS `default_project_templates`;
CREATE TABLE IF NOT EXISTS `default_project_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(250) NOT NULL,
  `company_id` int(250) NOT NULL,
  `module_name` varchar(250) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `default_project_template_cases`
--

DROP TABLE IF EXISTS `default_project_template_cases`;
CREATE TABLE IF NOT EXISTS `default_project_template_cases` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `company_id` int(250) NOT NULL,
  `template_id` int(11) NOT NULL,
  `title` text NOT NULL,
  `description` text NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `default_templates`
--

DROP TABLE IF EXISTS `default_templates`;
CREATE TABLE IF NOT EXISTS `default_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `default_templates`
--

INSERT INTO `default_templates` (`id`, `name`, `description`, `created`, `modified`) VALUES
(1, 'Meeting Minute', '<b>Attendees:</b>  John, Michael<br/>\n				<b>Date and Time:</b> July 11th 11 am PST<br/>\n				<b>Purpose:</b><br/>\n				\n				<br/>\n				<b>Agenda:</b> \n				<o>\n					<li>Discuss Layout </li>\n					<li>Discuss on Design</li>\n				</ol>\n				<br/>\n				<b>Discussion:</b><br/>', '2014-01-24 12:58:24', '2014-01-24 12:58:24'),
(2, 'Status update', '<p><strong>Today''s accomplishment:</strong></p>\n				<p><strong>&nbsp; &nbsp; &nbsp; Task no: 120</strong></p>\n				<ul>\n				<li>Lorem Ipsum is simply dummy text of the printing and typesetting industry</li>\n				<li>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout</li>\n				<li>Contrary to popular belief, Lorem Ipsum is not simply random text</li>\n				</ul>\n				<p>&nbsp; &nbsp; &nbsp;<strong>Task no: 125</strong></p>\n				<ul>\n				<li>Lorem Ipsum is simply dummy text of the printing and typesetting industry</li>\n				<li>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout</li>\n				<li>Contrary to popular belief, Lorem Ipsum is not simply random text</li>\n				</ul>\n				<p><br /> <strong>List of files changed:</strong></p>\n				<ol>\n				<li>index.html</li>\n				<li>style.css</li>\n				<li>contact-us.html</li>\n				</ol>\n				<p>Is code checked in Repository: <strong>Y/N</strong><br /> Is code available in Stager: <strong>Y/N</strong> </p>\n				<p><strong>Blocker/Testing Issues:</strong></p>\n				<p><strong>Milestone Update: &lt; Specify Milestone name here &gt;</strong></p>\n				<p>&nbsp; &nbsp;1. Total tasks:</p>\n				<p>&nbsp; &nbsp;2. # of Work in Progress tasks:</p>\n				<p>&nbsp; &nbsp;3. # of Resolve tasks:</p>\n				<p>&nbsp; &nbsp;4. # of tasks not started:</p>\n				<p><br /> <strong>Next Day''s Plan:</strong></p>', '2014-01-24 12:58:24', '2014-01-24 12:58:24'),
(3, 'Change Request', '<p><strong>Change Requested:</strong></p>\n				<p><strong>&nbsp; &nbsp; &nbsp; Task no: 120</strong></p>\n				<p><strong>&nbsp; &nbsp; &nbsp; Task no: 125</strong></p>\n				<p><strong>Today''s accomplishment:</strong></p>\n				<p><strong>&nbsp; &nbsp; &nbsp; Task no: 120</strong></p>\n				<ul>\n				<li>Lorem Ipsum is simply dummy text of the printing and typesetting industry</li>\n				<li>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout</li>\n				<li>Contrary to popular belief, Lorem Ipsum is not simply random text</li>\n				</ul>\n				<p>&nbsp; &nbsp; &nbsp;<strong>Task no: 125</strong></p>\n				<ul>\n				<li>Lorem Ipsum is simply dummy text of the printing and typesetting industry</li>\n				<li>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout</li>\n				<li>Contrary to popular belief, Lorem Ipsum is not simply random text</li>\n				</ul>\n				<p><br /> <strong>List of files changed:</strong></p>\n				<ol>\n				<li>index.html</li>\n				<li>style.css</li>\n				<li>contact-us.html</li>\n				</ol>\n				<p>Is code checked in Repository: <strong>Y/N</strong><br /> Is code available in Stager: <strong>Y/N</strong> </p>\n				<p><strong>Blocker/Testing Issues:</strong></p>\n				<p><strong>Milestone Update: &lt; Specify Milestone name here &gt;</strong></p>\n				<p>&nbsp; &nbsp;1. Total tasks:</p>\n				<p>&nbsp; &nbsp;2. # of Work in Progress tasks:</p>\n				<p>&nbsp; &nbsp;3. # of Resolve tasks:</p>\n				<p>&nbsp; &nbsp;4. # of tasks not started:</p>\n				<p><br /> <strong>Next Day''s Plan:</strong></p>', '2014-01-24 12:58:24', '2014-01-24 12:58:24'),
(4, 'Bug', '<b>Browser version:</b>\n				<br/>\n				<b>OS version:</b>\n				<br/><br/>\n				<b>Url:</b>\n				<br/><br/>\n				<b>What is the test case:</b><br/>\n				<b>What is the expected result:</b><br/>\n				<b>What is the actual result:</b><br/><br/>\n				\n				<b>Is it happening all the time or intermittently:</b><br/>\n				<br/>\n				<b>Attach screenshots:</b>', '2014-01-24 12:58:24', '2014-01-24 12:58:24');

-- --------------------------------------------------------

--
-- Table structure for table `easycases`
--

DROP TABLE IF EXISTS `easycases`;
CREATE TABLE IF NOT EXISTS `easycases` (
  `id` mediumint(11) NOT NULL AUTO_INCREMENT,
  `uniq_id` varchar(64) NOT NULL,
  `case_no` int(11) NOT NULL,
  `case_count` int(11) NOT NULL,
  `project_id` int(11) NOT NULL COMMENT 'Foreign key of "projects"',
  `user_id` int(11) NOT NULL COMMENT 'Foreign key of "users"',
  `updated_by` int(11) NOT NULL,
  `type_id` tinyint(4) NOT NULL COMMENT 'Foreign key of "case_types"',
  `priority` varchar(4) DEFAULT NULL,
  `title` varchar(256) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `message` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `estimated_hours` decimal(6,1) NOT NULL,
  `hours` decimal(6,1) NOT NULL,
  `completed_task` int(11) NOT NULL DEFAULT '0',
  `assign_to` int(11) NOT NULL COMMENT 'Foreign Key of "users"',
  `due_date` date DEFAULT NULL,
  `istype` tinyint(2) NOT NULL DEFAULT '1' COMMENT '1-Post, 2-Comment',
  `format` tinyint(2) NOT NULL DEFAULT '1' COMMENT '1-Files&Details, 2-Details, 3-Files',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '1-Open, 2-Closed',
  `legend` tinyint(2) NOT NULL COMMENT '1-New 2-Opened, 3-Closed, 4-Start, 5-Resolve, 6- Modified',
  `isactive` tinyint(2) NOT NULL DEFAULT '1' COMMENT '0-Inactive,1-Active',
  `dt_created` datetime NOT NULL,
  `actual_dt_created` datetime NOT NULL,
  `reply_type` int(11) NOT NULL DEFAULT '0' COMMENT '1-> Case Type changes, 2-> Assign to , 3 -> Due Date,4 -> Priority ',
  `is_chrome_extension` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0: No, 1: Yes',
  `from_email` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `uniq_id` (`uniq_id`),
  KEY `case_no` (`case_no`),
  KEY `project_id` (`project_id`),
  KEY `user_id` (`user_id`),
  KEY `isactive` (`isactive`),
  KEY `legend` (`legend`),
  KEY `status` (`status`),
  KEY `format` (`format`),
  KEY `istype` (`istype`),
  KEY `assign_to` (`assign_to`),
  KEY `priority` (`priority`),
  KEY `type_id` (`type_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `easycase_milestones`
--

DROP TABLE IF EXISTS `easycase_milestones`;
CREATE TABLE IF NOT EXISTS `easycase_milestones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `easycase_id` int(11) NOT NULL,
  `milestone_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `id_seq` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `easycase_id` (`easycase_id`),
  KEY `milestone_id` (`milestone_id`),
  KEY `project_id` (`project_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `email_reminders`
--

DROP TABLE IF EXISTS `email_reminders`;
CREATE TABLE IF NOT EXISTS `email_reminders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `email_type` int(11) NOT NULL COMMENT '1:Aaccount Activation, 2:login, 3:Project Creation, 4:User Add, 5:Task Add',
  `cron_date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `log_activities`
--

DROP TABLE IF EXISTS `log_activities`;
CREATE TABLE IF NOT EXISTS `log_activities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `log_type_id` int(11) DEFAULT NULL,
  `json_value` text COLLATE utf8_unicode_ci,
  `created` datetime DEFAULT NULL,
  `ip` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `log_types`
--

DROP TABLE IF EXISTS `log_types`;
CREATE TABLE IF NOT EXISTS `log_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=36 ;

--
-- Dumping data for table `log_types`
--

INSERT INTO `log_types` (`id`, `name`, `created`) VALUES
(1, 'Account Created', '2013-07-24 17:20:55'),
(3, 'User Deleted', '2013-07-24 17:20:55'),
(4, 'Plan Upgraded', '2013-07-24 17:24:48'),
(5, 'Braintree Profile Created', '2013-07-24 17:24:48'),
(6, 'Credit Card Updated', '2013-07-24 17:24:48'),
(7, 'Subscription Created', '2013-07-24 17:24:48'),
(8, 'Subscription Updated', '2013-07-24 17:24:48'),
(9, 'Invoice Generated', '2013-07-24 17:24:48'),
(10, 'Invoice Failed', '2013-07-24 17:24:48'),
(11, 'Subscription Expired', '2013-07-24 17:24:48'),
(12, 'Subscription canceled', '2013-07-24 17:24:48'),
(13, 'Subscription trial ended', '2013-07-24 17:24:48'),
(14, 'Subscription went active', '2013-07-24 17:24:48'),
(17, 'Invoice Email Sent', '2013-07-24 17:24:48'),
(18, 'Invoice Email Faild ', '2013-07-24 17:24:48'),
(19, 'Cancel subscription notification mail sent ', '2013-07-24 17:24:48'),
(20, 'Instant payment after cancel subscription', '2013-07-24 17:24:48'),
(21, 'Expiry date notification mail sent', '2013-07-24 17:24:48'),
(22, 'Instant payment invoice mail sent ', '2013-07-24 17:24:48'),
(23, 'Instant payment invoice mail faild ', '2013-07-24 17:24:48'),
(24, 'Account confirmed', '2013-09-05 18:41:14'),
(25, 'User invited', '2013-09-06 10:28:25'),
(26, 'User invitation confirmed', '2013-09-06 11:57:00'),
(27, 'User disabled', '2013-09-06 12:17:00'),
(28, 'User enabled', '2013-09-06 12:17:12'),
(29, 'Cancel subscription notification mail faild', '2013-09-06 16:08:33'),
(30, 'Credit Card expired', '2013-09-14 11:38:31'),
(31, 'Credit Card Reminder mail sent', '2013-09-14 11:38:26'),
(32, 'Subscription Payment Failed', '0000-00-00 00:00:00'),
(33, 'Account Deactivated', '0000-00-00 00:00:00'),
(34, 'Account Disable By Admin', '0000-00-00 00:00:00'),
(35, 'Plan downgraded', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `mail_tbls`
--

DROP TABLE IF EXISTS `mail_tbls`;
CREATE TABLE IF NOT EXISTS `mail_tbls` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mail` varchar(250) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `milestones`
--

DROP TABLE IF EXISTS `milestones`;
CREATE TABLE IF NOT EXISTS `milestones` (
  `id` int(250) NOT NULL AUTO_INCREMENT,
  `uniq_id` varchar(250) NOT NULL,
  `project_id` int(250) NOT NULL,
  `company_id` int(11) NOT NULL,
  `title` varchar(250) NOT NULL,
  `description` text NOT NULL,
  `user_id` int(250) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `isactive` tinyint(2) NOT NULL DEFAULT '1' COMMENT '0-Inactive, 1-Active',
  `id_seq` tinyint(2) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_id` (`uniq_id`),
  KEY `project_id` (`project_id`),
  KEY `user_id` (`user_id`),
  KEY `company_id` (`company_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

DROP TABLE IF EXISTS `projects`;
CREATE TABLE IF NOT EXISTS `projects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniq_id` varchar(64) NOT NULL,
  `user_id` int(11) NOT NULL COMMENT 'Foreign key of "users"',
  `company_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `short_name` varchar(10) NOT NULL,
  `description` text NOT NULL,
  `logo` varchar(50) NOT NULL,
  `project_type` tinyint(2) NOT NULL DEFAULT '1' COMMENT '1-Internal, 2-External',
  `default_assign` int(255) NOT NULL,
  `isactive` tinyint(2) NOT NULL DEFAULT '1' COMMENT '1- Active, 2- Inactive',
  `dt_created` datetime NOT NULL,
  `dt_updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `uniq_id` (`uniq_id`),
  KEY `user_id` (`user_id`),
  KEY `isactive` (`isactive`),
  KEY `project_type` (`project_type`),
  KEY `company_id` (`company_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `project_technologies`
--

DROP TABLE IF EXISTS `project_technologies`;
CREATE TABLE IF NOT EXISTS `project_technologies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL COMMENT 'Foreign key of "projects"',
  `technology_id` smallint(6) NOT NULL COMMENT 'Foreign key of "technologies"',
  PRIMARY KEY (`id`),
  KEY `project_id` (`project_id`),
  KEY `tech_proj_id` (`technology_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `project_templates`
--

DROP TABLE IF EXISTS `project_templates`;
CREATE TABLE IF NOT EXISTS `project_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(250) NOT NULL,
  `company_id` int(250) NOT NULL,
  `module_name` varchar(250) NOT NULL,
  `is_default` tinyint(4) NOT NULL COMMENT '0-default,1-not',
  `is_active` tinyint(2) NOT NULL COMMENT '1-active,0-inactive',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `project_template_cases`
--

DROP TABLE IF EXISTS `project_template_cases`;
CREATE TABLE IF NOT EXISTS `project_template_cases` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `company_id` int(250) NOT NULL,
  `template_id` int(11) NOT NULL,
  `title` text NOT NULL,
  `description` text NOT NULL,
  `sort` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `project_users`
--

DROP TABLE IF EXISTS `project_users`;
CREATE TABLE IF NOT EXISTS `project_users` (
  `id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL COMMENT 'Foreign key of "projects"',
  `company_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL COMMENT 'Foreign key of "users"',
  `istype` tinyint(2) NOT NULL DEFAULT '2' COMMENT '1-Admin, 2-Moderator, 3-Viewer',
  `default_email` tinyint(2) NOT NULL DEFAULT '1' COMMENT '0-No, 1-Yes',
  `dt_visited` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `project_id` (`project_id`),
  KEY `user_id` (`user_id`),
  KEY `istype` (`istype`),
  KEY `company_id` (`company_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `save_reports`
--

DROP TABLE IF EXISTS `save_reports`;
CREATE TABLE IF NOT EXISTS `save_reports` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `rpt_type` tinyint(4) NOT NULL COMMENT '1:Task 2:Hour 3:Bug 4:Project',
  `frm_dt` date NOT NULL,
  `to_dt` date NOT NULL,
  `created` datetime NOT NULL,
  `ip` varchar(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
--

DROP TABLE IF EXISTS `subscriptions`;
CREATE TABLE IF NOT EXISTS `subscriptions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plan` int(11) NOT NULL COMMENT '1-Demo, 2-PRO, 3-LITE, 4-PREMIUM',
  `storage` varchar(250) NOT NULL COMMENT 'Mb',
  `project_limit` varchar(100) NOT NULL,
  `user_limit` varchar(100) NOT NULL,
  `milestone_limit` varchar(50) NOT NULL,
  `free_trail_days` smallint(6) NOT NULL,
  `price` smallint(6) NOT NULL,
  `month` smallint(6) NOT NULL,
  `is_active` tinyint(2) NOT NULL DEFAULT '1' COMMENT '0-Inactive,1-Active',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `subscriptions`
--

INSERT INTO `subscriptions` (`id`, `plan`, `storage`, `project_limit`, `user_limit`, `milestone_limit`, `free_trail_days`, `price`, `month`, `is_active`, `created`, `modified`) VALUES
(1, 1, 'Unlimited', 'Unlimited', 'Unlimited', 'Unlimited', 0, 0, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `template_module_cases`
--

DROP TABLE IF EXISTS `template_module_cases`;
CREATE TABLE IF NOT EXISTS `template_module_cases` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `company_id` int(250) NOT NULL,
  `template_module_id` int(250) NOT NULL COMMENT 'foreign key of template_module',
  `project_id` int(11) NOT NULL COMMENT 'foreign key of Project table.',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `timezones`
--

DROP TABLE IF EXISTS `timezones`;
CREATE TABLE IF NOT EXISTS `timezones` (
  `id` int(11) NOT NULL DEFAULT '0',
  `gmt_offset` double DEFAULT '0',
  `dst_offset` double DEFAULT NULL,
  `code` varchar(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `timezones`
--

INSERT INTO `timezones` (`id`, `gmt_offset`, `dst_offset`, `code`) VALUES
(1, -12, 0, NULL),
(2, -11, 0, NULL),
(3, -10, 0, 'H'),
(4, -9, 1, 'AK'),
(5, -8, 1, 'P'),
(6, -7, 0, 'M'),
(7, -7, 1, NULL),
(8, -7, 1, 'M'),
(9, -6, 0, NULL),
(10, -6, 1, 'C'),
(11, -6, 1, NULL),
(12, -6, 0, 'C'),
(13, -5, 0, NULL),
(14, -5, 1, 'E'),
(15, -5, 0, 'E'),
(16, -4, 1, 'A'),
(17, -4, 0, NULL),
(18, -4, 1, NULL),
(19, -3.5, 1, 'N'),
(20, -3, 1, NULL),
(21, -3, 0, NULL),
(22, -3, 1, NULL),
(23, -2, 1, NULL),
(24, -1, 1, NULL),
(25, -1, 0, NULL),
(26, 0, 0, NULL),
(27, 0, 1, NULL),
(28, 1, 1, NULL),
(29, 1, 1, NULL),
(30, 1, 1, NULL),
(31, 1, 1, NULL),
(32, 1, 0, NULL),
(33, 2, 1, NULL),
(34, 2, 1, NULL),
(35, 2, 1, NULL),
(36, 2, 0, NULL),
(37, 2, 1, NULL),
(38, 2, 0, NULL),
(39, 3, 1, NULL),
(40, 3, 0, NULL),
(41, 3, 1, NULL),
(42, 3, 0, NULL),
(43, 3.5, 1, NULL),
(44, 4, 0, NULL),
(45, 4, 1, NULL),
(46, 4.5, 0, NULL),
(47, 5, 1, NULL),
(48, 5, 0, NULL),
(49, 5.5, 0, NULL),
(50, 5.75, 0, NULL),
(51, 6, 1, NULL),
(52, 6, 0, NULL),
(53, 6, 0, NULL),
(54, 6.5, 0, NULL),
(55, 7, 0, NULL),
(56, 7, 1, NULL),
(57, 8, 0, NULL),
(58, 8, 1, NULL),
(59, 8, 0, NULL),
(60, 8, 0, NULL),
(61, 8, 0, NULL),
(62, 9, 0, NULL),
(63, 9, 0, NULL),
(64, 9, 1, NULL),
(65, 9.5, 1, NULL),
(66, 9.5, 0, NULL),
(67, 10, 0, NULL),
(68, 10, 1, NULL),
(69, 10, 0, NULL),
(70, 10, 1, NULL),
(71, 10, 1, NULL),
(72, 11, 0, NULL),
(73, 12, 1, NULL),
(74, 12, 0, NULL),
(75, 13, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `timezone_names`
--

DROP TABLE IF EXISTS `timezone_names`;
CREATE TABLE IF NOT EXISTS `timezone_names` (
  `id` int(11) NOT NULL,
  `gmt` varchar(15) NOT NULL,
  `zone` varchar(100) NOT NULL,
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `timezone_names`
--

INSERT INTO `timezone_names` (`id`, `gmt`, `zone`) VALUES
(1, '(GMT-12:00)', 'International Date Line West'),
(2, '(GMT-11:00)', 'Midway Island Samoa'),
(3, '(GMT-10:00)', 'Hawaii'),
(4, '(GMT-9:00)', 'Alaska'),
(5, '(GMT-08:00)', 'Pacific Time (US & Canada); Tijuana'),
(6, '(GMT-07:00)', 'Arizona'),
(7, '(GMT-07:00)', 'Chihuahua, La Paz, Mazatlan'),
(8, '(GMT-07:00)', 'Mountain Time (US & Canada)'),
(9, '(GMT-06:00)', 'Central America'),
(10, '(GMT-06:00)', 'Central Time (US & Canada)'),
(11, '(GMT-06:00)', 'Guadalajara, Mexico City, Monterrey'),
(12, '(GMT-06:00)', 'Saskatchewan'),
(13, '(GMT-05:00)', 'Bogota, Lime, Quito'),
(14, '(GMT-05:00)', 'Eastern Time (US & Canada)'),
(15, '(GMT-05:00)', 'Indiana (East)'),
(16, '(GMT-05:04)', 'Atlantic Time (Canada)'),
(17, '(GMT-04:00)', 'Caracas, La Paz'),
(18, '(GMT-04:00)', 'Santiago'),
(19, '(GMT-03:30)', 'Newfoundland'),
(20, '(GMT-03:00)', 'Brasilia'),
(21, '(GMT-03:00)', 'Buenos Aires, Georgetown'),
(22, '(GMT-03:00)', 'Greenland'),
(23, '(GMT-02:00)', 'Mid-Atlantic'),
(24, '(GMT-01:00)', 'Azores'),
(25, '(GMT-01:00)', 'Cape Verde Is.'),
(26, '(GMT)', 'Casablanca, Monrovia'),
(27, '(GMT)', 'Greenwich Mean Time : Dublin, Edinburgh, Lisbon, London'),
(28, '(GMT+01:00)', 'Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna'),
(29, '(GMT+01:00)', 'Belgrade, Bratislava, Budapest, Ljubljana, Prague'),
(30, '(GMT+01:00)', 'Brussels, Copenhagen, Madrid, Paris'),
(31, '(GMT+01:00)', 'Sarajevo, Skopje, Warsaw, Zagreb'),
(32, '(GMT+01:00)', 'West Central Africa'),
(33, '(GMT+02:00)', 'Athens, Istanbul, Minsk'),
(34, '(GMT+02:00)', 'Bucharest'),
(35, '(GMT+02:00)', 'Cairo'),
(36, '(GMT+02:00)', 'Harare, Pretoria'),
(37, '(GMT+02:00)', 'Helsinki, Kyiv, Riga, Sofia, Tallinn, Vilnius'),
(38, '(GMT+02:00)', 'Jerusalem'),
(39, '(GMT+03:00)', 'Baghdad'),
(40, '(GMT+03:00)', 'Kuwait, Riyadh'),
(41, '(GMT+03:00)', 'Moscow, St. Petersburg, Volgograd'),
(42, '(GMT+03:00)', 'Nairobi'),
(43, '(GMT+03:30)', 'Tehran'),
(44, '(GMT+04:00)', 'Abu Dhabi, Muscat'),
(45, '(GMT+04:00)', 'Baku, Tbilisi, Yerevan'),
(46, '(GMT+04:30)', 'Kabul'),
(47, '(GMT+05:00)', 'Ekaterinburg'),
(48, '(GMT+05:00)', 'Islamabad, Karachi, Tashkent'),
(49, '(GMT+05:30)', 'Chennai, Kolkata, Mumbai, New Delhi'),
(50, '(GMT+05.75)', 'Kathmandu'),
(51, '(GMT+06:00)', 'Almaty, Novosibirsk'),
(52, '(GMT+06:00)', 'Astana, Dhaka'),
(53, '(GMT+06:00)', 'Sri Jayawardenepura'),
(54, '(GMT+06:30)', 'Rangoon'),
(55, '(GMT+07:00)', 'Bangkok, Hanoi, Jakarta'),
(56, '(GMT+07:00)', 'Krasnoyarsk'),
(57, '(GMT+08:00)', 'Beijing, Chongging, Hong Kong, Urumgi'),
(58, '(GMT+08:00)', 'Irkutsk, Ulaan Bataar'),
(59, '(GMT+08:00)', 'Kuala Lumpur, Singapore'),
(60, '(GMT+08:00)', 'Perth'),
(61, '(GMT+08:00)', 'Taipei'),
(62, '(GMT+09:00)', 'Osaka, Sapporo, Tokyo'),
(63, '(GMT+09:00)', 'Seoul'),
(64, '(GMT+09:00)', 'Yakutsk'),
(65, '(GMT+09:30)', 'Adelaide'),
(66, '(GMT+09:30)', 'Darwin'),
(67, '(GMT+10:00)', 'Brisbane'),
(68, '(GMT+10:00)', 'Canberra, Melbourne, Sydney'),
(69, '(GMT+10:00)', 'Guam, Port Moresby'),
(70, '(GMT+10:00)', 'Hobart'),
(71, '(GMT+10:00)', 'Vladivostok'),
(72, '(GMT+11:00)', 'Magadan, Solomon Is., New Caledonia'),
(73, '(GMT+12:00)', 'Auckland, Wellington'),
(74, '(GMT+12:00)', 'Figi, Kamchatka, Marshall Is.'),
(75, '(GMT+13:00)', 'Nuku''alofa');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

DROP TABLE IF EXISTS `transactions`;
CREATE TABLE IF NOT EXISTS `transactions` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `btsubscription_id` varchar(10) NOT NULL,
  `transaction_id` varchar(100) NOT NULL,
  `plan_id` int(11) DEFAULT NULL,
  `status` varchar(100) NOT NULL,
  `subscription_price` float(4,2) DEFAULT NULL,
  `amt` float(7,2) NOT NULL,
  `discount` float(4,2) DEFAULT NULL,
  `addon` float(4,2) DEFAULT NULL,
  `transaction_type` varchar(100) NOT NULL,
  `invoice_mail_flag` tinyint(2) DEFAULT '0',
  `invoice_id` varchar(255) DEFAULT NULL,
  `created` datetime NOT NULL,
  `ip` varchar(20) NOT NULL,
  `company_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `types`
--

DROP TABLE IF EXISTS `types`;
CREATE TABLE IF NOT EXISTS `types` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL DEFAULT '0',
  `short_name` varchar(10) NOT NULL,
  `name` varchar(50) NOT NULL,
  `seq_order` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `types`
--

INSERT INTO `types` (`id`, `company_id`, `short_name`, `name`, `seq_order`) VALUES
(1, 0, 'bug', 'Bug', 2),
(2, 0, 'dev', 'Development', 1),
(3, 0, 'enh', 'Enhancement', 6),
(4, 0, 'rnd', 'Research n Do', 7),
(5, 0, 'qa', 'Quality Assurance', 9),
(6, 0, 'unt', 'Unit Testing', 10),
(7, 0, 'mnt', 'Maintenance', 8),
(8, 0, 'oth', 'Others', 12),
(9, 0, 'rel', 'Release', 11),
(10, 0, 'upd', 'Update', 3),
(11, 0, 'idea', 'Idea', 5),
(12, 0, 'cr', 'Change Request', 4);

-- --------------------------------------------------------

--
-- Table structure for table `type_companies`
--

DROP TABLE IF EXISTS `type_companies`;
CREATE TABLE IF NOT EXISTS `type_companies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniq_id` varchar(64) NOT NULL,
  `btprofile_id` varchar(100) DEFAULT NULL,
  `usersubscription_id` int(10) DEFAULT NULL,
  `credit_cardtoken` varchar(100) DEFAULT NULL,
  `card_number` varchar(255) DEFAULT NULL COMMENT 'Only last 4degit of card',
  `expiry_date` varchar(255) DEFAULT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(64) DEFAULT NULL,
  `name` varchar(150) NOT NULL,
  `is_beta` tinyint(2) NOT NULL DEFAULT '0' COMMENT '1 For beta user ,0 - Default',
  `last_name` varchar(100) DEFAULT NULL,
  `short_name` varchar(8) DEFAULT NULL,
  `istype` tinyint(2) NOT NULL DEFAULT '3' COMMENT '1-Super Admin, 2-Internal User, 3-External User',
  `photo` varchar(50) DEFAULT NULL,
  `photo_reset` varchar(50) DEFAULT NULL,
  `isactive` tinyint(2) NOT NULL DEFAULT '1' COMMENT '1 - ACTIVE, 2 - INACTIVE . 2-Disabled ,3- Deleted user',
  `timezone_id` smallint(6) DEFAULT '26',
  `isemail` tinyint(2) NOT NULL DEFAULT '1' COMMENT '1-Send Email, 0-Don''t Send',
  `is_agree` tinyint(2) NOT NULL DEFAULT '1' COMMENT '0: No, 1: Yes',
  `usersub_type` tinyint(4) DEFAULT '0' COMMENT '0->Free User, 1->Paid User',
  `est_billing_amount` float(10,2) DEFAULT '0.00',
  `dt_created` datetime NOT NULL,
  `dt_updated` datetime DEFAULT NULL,
  `dt_last_login` datetime DEFAULT NULL,
  `dt_last_logout` datetime DEFAULT NULL,
  `query_string` varchar(100) DEFAULT NULL,
  `update_email` varchar(150) NOT NULL,
  `update_random` varchar(150) NOT NULL,
  `gaccess_token` text,
  `ip` varchar(15) DEFAULT NULL,
  `sig` varchar(100) NOT NULL,
  `desk_notify` tinyint(4) NOT NULL DEFAULT '1',
  `active_dashboard_tab` int(11) NOT NULL DEFAULT '7' COMMENT 'Sum of Binary values which will show tabs accordingly',
  `is_moderator` tinyint(3) NOT NULL DEFAULT '0' COMMENT '0: No grant privilege, 1: Yes',
  `show_default_inner` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_id_2` (`uniq_id`),
  KEY `uniq_id` (`uniq_id`),
  KEY `istype` (`istype`),
  KEY `isactive` (`isactive`),
  KEY `isemail` (`isemail`),
  KEY `timezone_id` (`timezone_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_infos`
--

DROP TABLE IF EXISTS `user_infos`;
CREATE TABLE IF NOT EXISTS `user_infos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `access_token` text,
  `is_google_signup` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_invitations`
--

DROP TABLE IF EXISTS `user_invitations`;
CREATE TABLE IF NOT EXISTS `user_invitations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `invitor_id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `user_type` tinyint(2) NOT NULL DEFAULT '3',
  `project_id` text COMMENT 'Comma separated value of project ids',
  `user_id` int(11) NOT NULL,
  `is_active` tinyint(2) NOT NULL DEFAULT '1',
  `qstr` varchar(100) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `company_id` (`company_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_logins`
--

DROP TABLE IF EXISTS `user_logins`;
CREATE TABLE IF NOT EXISTS `user_logins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `user_logins`
--

INSERT INTO `user_logins` (`id`, `user_id`, `created`) VALUES
(1, 1, '2014-09-15 09:59:39'),
(2, 1, '2014-09-15 10:07:08'),
(3, 1, '2014-09-15 10:10:52'),
(4, 1, '2014-09-17 10:45:21'),
(5, 1, '2014-09-18 05:41:54');

-- --------------------------------------------------------

--
-- Table structure for table `user_notifications`
--

DROP TABLE IF EXISTS `user_notifications`;
CREATE TABLE IF NOT EXISTS `user_notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1-Email',
  `value` tinyint(4) NOT NULL COMMENT '0-None, 1-Daily, 2-Weekly, 3-Monthly',
  `due_val` tinyint(4) NOT NULL COMMENT '0-Not,1-send',
  `new_case` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0-No, 1-Yes',
  `reply_case` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0-No, 1-Yes',
  `case_status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0-No, 1-Yes',
  `weekly_usage_alert` tinyint(2) NOT NULL DEFAULT '1' COMMENT 'Only for owner/admins 1-> Default on 0-> Off ',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_subscriptions`
--

DROP TABLE IF EXISTS `user_subscriptions`;
CREATE TABLE IF NOT EXISTS `user_subscriptions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `subscription_id` tinyint(4) NOT NULL,
  `storage` varchar(50) NOT NULL,
  `project_limit` varchar(50) NOT NULL,
  `user_limit` varchar(50) NOT NULL,
  `milestone_limit` varchar(50) NOT NULL,
  `free_trail_days` varchar(15) NOT NULL,
  `price` float(10,2) NOT NULL DEFAULT '0.00',
  `month` smallint(6) NOT NULL,
  `amt_due` float(10,2) NOT NULL DEFAULT '0.00',
  `coupon_code` varchar(100) DEFAULT NULL,
  `btsubscription_id` varchar(100) DEFAULT NULL,
  `btprofile_id` varchar(255) DEFAULT NULL,
  `creditcard_token` varchar(255) DEFAULT '',
  `payment_status` tinyint(2) DEFAULT '0',
  `discount` float(10,2) DEFAULT NULL,
  `is_cancel` tinyint(2) DEFAULT '0' COMMENT '1->Cancelled',
  `is_sub_upgraded_bt` tinyint(2) DEFAULT '0',
  `cancel_mail_flag` tinyint(2) DEFAULT '0',
  `no_of_atmpt_upgrd` tinyint(2) DEFAULT NULL,
  `is_free` tinyint(2) NOT NULL DEFAULT '0',
  `is_updown` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0->Cancelled , 1-> Upgraded ,2-> Downgrad',
  `sub_start_date` datetime DEFAULT NULL,
  `next_billing_date` date DEFAULT NULL,
  `cancel_date` date DEFAULT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `company_id` (`company_id`),
  KEY `subscription_id` (`subscription_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Database: `os_security`
--

-- --------------------------------------------------------

--
-- Table structure for table `os_session_logs`
--

CREATE TABLE IF NOT EXISTS `os_session_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_agent` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

