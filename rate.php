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

use Xmf\Request;
use XoopsModules\Service;
use XoopsModules\Service\Constants;

require __DIR__ . '/header.php';
$op = Request::getCmd('op', 'list');
$source = Request::getInt('source', 0);
switch ($op) {
	case 'list':
	default:
		// default should not happen
		redirect_header('index.php', 3, _NOPERM);
		break;
	case 'save':
		// Security Check
		if ($GLOBALS['xoopsSecurity']->check()) {
			redirect_header('index.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
		}
		$rating = Request::getInt('rating', 0);
		$itemid = 0;
		$redir  = $_SERVER['HTTP_REFERER'];
		if (Constants::TABLE_SERVICES === $source) {
			$itemid = Request::getInt('ser_id', 0);
			$redir = 'services.php?op=show&amp;ser_id=' . $itemid;
		}

		// Check permissions
		$rate_allowed = false;
		$groups = (isset($GLOBALS['xoopsUser']) && is_object($GLOBALS['xoopsUser'])) ? $GLOBALS['xoopsUser']->getGroups() : XOOPS_GROUP_ANONYMOUS;
		foreach ($groups as $group) {
			if (XOOPS_GROUP_ADMIN == $group || in_array($group, $helper->getConfig('ratingbar_groups'))) {
				$rate_allowed = true;
				break;
			}
		}
		if (!$rate_allowed) {
			redirect_header('index.php', 3, _MA_SERVICE_RATING_NOPERM);
		}

		// Check rating value
		switch ((int)$helper->getConfig('ratingbars')) {
			case Constants::RATING_NONE:
			default:
				redirect_header('index.php', 3, _MA_SERVICE_RATING_VOTE_BAD);
				exit;
				break;
			case Constants::RATING_LIKES:
				if ($rating > 1 || $rating < -1) {
					redirect_header('index.php', 3, _MA_SERVICE_RATING_VOTE_BAD);
					exit;
				}
				break;
			case Constants::RATING_5STARS:
				if ($rating > 5 || $rating < 1) {
					redirect_header('index.php', 3, _MA_SERVICE_RATING_VOTE_BAD);
					exit;
				}
				break;
			case Constants::RATING_10STARS:
			case Constants::RATING_10NUM:
				if ($rating > 10 || $rating < 1) {
					redirect_header('index.php', 3, _MA_SERVICE_RATING_VOTE_BAD);
					exit;
				}
				break;
		}

		// Get existing rating
		$itemrating = $ratingsHandler->getItemRating($itemid, $source);

		// Set data rating
		if ($itemrating['voted']) {
			// If yo want to avoid revoting then activate next line
			//redirect_header('index.php', 3, _MA_SERVICE_RATING_VOTE_BAD);
			$ratingsObj = $ratingsHandler->get($itemrating['id']);
		} else {
			$ratingsObj = $ratingsHandler->create();
		}
		$ratingsObj->setVar('rate_source', $source);
		$ratingsObj->setVar('rate_itemid', $itemid);
		$ratingsObj->setVar('rate_value', $rating);
		$ratingsObj->setVar('rate_uid', $itemrating['uid']);
		$ratingsObj->setVar('rate_ip', $itemrating['ip']);
		$ratingsObj->setVar('rate_date', time());
		// Insert Data
		if ($ratingsHandler->insert($ratingsObj)) {
			unset($ratingsObj);
			// Calc average rating value
			$nb_ratings     = 0;
			$avg_rate_value = 0;
			$current_rating = 0;
			$crRatings = new \CriteriaCompo();
			$crRatings->add(new \Criteria('rate_source', $source));
			$crRatings->add(new \Criteria('rate_itemid', $itemid));
			$ratingsCount = $ratingsHandler->getCount($crRatings);
			$ratingsAll = $ratingsHandler->getAll($crRatings);
			foreach (array_keys($ratingsAll) as $i) {
				$current_rating += $ratingsAll[$i]->getVar('rate_value');
			}
			unset($ratingsAll);
			if ($ratingsCount > 0) {
				$avg_rate_value = number_format($current_rating / $ratingsCount, 2);
			}
			// Update related table
			if (Constants::TABLE_SERVICES === $source) {
				$tableName = 'services';
				$fieldRatings = 'ser_ratings';
				$fieldVotes   = 'ser_votes';
				$servicesObj = $servicesHandler->get($itemid);
				$servicesObj->setVar('ser_ratings', $avg_rate_value);
				$servicesObj->setVar('ser_votes', $ratingsCount);
				if ($servicesHandler->insert($servicesObj)) {
					redirect_header($redir, 2, _MA_SERVICE_RATING_VOTE_THANKS);
				} else {
					redirect_header('services.php', 3, _MA_SERVICE_RATING_ERROR1);
				}
				unset($servicesObj);
			}

			redirect_header('index.php', 2, _MA_SERVICE_RATING_VOTE_THANKS);
		}
		// Get Error
		echo 'Error: ' . $ratingsObj->getHtmlErrors();
		break;
}
require __DIR__ . '/footer.php';
