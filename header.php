<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 * Service module for xoops
 *
 * @copyright      2020 XOOPS Project (https://xooops.org)
 * @license        GPL 2.0 or later
 * @package        service
 * @since          1.0
 * @min_xoops      2.5.9
 * @author         B.Heyula - Email:<b.heyula@hotmail.com> - Website:<http://erenyumak.com>
 */
include dirname(dirname(__DIR__)) . '/mainfile.php';
include __DIR__ . '/include/common.php';
$moduleDirName = basename(__DIR__);
// Breadcrumbs
$xoBreadcrumbs = [];
$xoBreadcrumbs[] = ['title' => _MA_SERVICE_TITLE, 'link' => SERVICE_URL . '/'];
// Get instance of module
$helper = \XoopsModules\Service\Helper::getInstance();
$categoriesHandler = $helper->getHandler('Categories');
$servicesHandler = $helper->getHandler('Services');
$permissionsHandler = $helper->getHandler('Permissions');
$ratingsHandler = $helper->getHandler('Ratings');
// 
$myts = MyTextSanitizer::getInstance();
// Default Css Style
$style = SERVICE_URL . '/assets/css/style.css';
// Smarty Default
$sysPathIcon16 = $GLOBALS['xoopsModule']->getInfo('sysicons16');
$sysPathIcon32 = $GLOBALS['xoopsModule']->getInfo('sysicons32');
$pathModuleAdmin = $GLOBALS['xoopsModule']->getInfo('dirmoduleadmin');
$modPathIcon16 = $GLOBALS['xoopsModule']->getInfo('modicons16');
$modPathIcon32 = $GLOBALS['xoopsModule']->getInfo('modicons16');
// Load Languages
xoops_loadLanguage('main');
xoops_loadLanguage('modinfo');
