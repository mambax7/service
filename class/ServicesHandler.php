<?php

namespace XoopsModules\Service;

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

use XoopsModules\Service;


/**
 * Class Object Handler Services
 */
class ServicesHandler extends \XoopsPersistableObjectHandler
{
	/**
	 * Constructor
	 *
	 * @param \XoopsDatabase $db
	 */
	public function __construct(\XoopsDatabase $db)
	{
		parent::__construct($db, 'service_services', Services::class, 'ser_id', 'ser_cat');
	}

	/**
	 * @param bool $isNew
	 *
	 * @return object
	 */
	public function create($isNew = true)
	{
		return parent::create($isNew);
	}

	/**
	 * retrieve a field
	 *
	 * @param int $i field id
	 * @param null fields
	 * @return mixed reference to the {@link Get} object
	 */
	public function get($i = null, $fields = null)
	{
		return parent::get($i, $fields);
	}

	/**
	 * get inserted id
	 *
	 * @param null
	 * @return int reference to the {@link Get} object
	 */
	public function getInsertId()
	{
		return $this->db->getInsertId();
	}

	/**
	 * Get Count Services in the database
	 * @param int    $start
	 * @param int    $limit
	 * @param string $sort
	 * @param string $order
	 * @return int
	 */
	public function getCountServices($start = 0, $limit = 0, $sort = 'ser_id ASC, ser_cat', $order = 'ASC')
	{
		$crCountServices = new \CriteriaCompo();
		$crCountServices = $this->getServicesCriteria($crCountServices, $start, $limit, $sort, $order);
		return $this->getCount($crCountServices);
	}

	/**
	 * Get All Services in the database
	 * @param int    $start
	 * @param int    $limit
	 * @param string $sort
	 * @param string $order
	 * @return array
	 */
	public function getAllServices($start = 0, $limit = 0, $sort = 'ser_id ASC, ser_cat', $order = 'ASC')
	{
		$crAllServices = new \CriteriaCompo();
		$crAllServices = $this->getServicesCriteria($crAllServices, $start, $limit, $sort, $order);
		return $this->getAll($crAllServices);
	}

	/**
	 * Get Criteria Services
	 * @param        $crServices
	 * @param int    $start
	 * @param int    $limit
	 * @param string $sort
	 * @param string $order
	 * @return int
	 */
	private function getServicesCriteria($crServices, $start, $limit, $sort, $order)
	{
		$crServices->setStart($start);
		$crServices->setLimit($limit);
		$crServices->setSort($sort);
		$crServices->setOrder($order);
		return $crServices;
	}
}
