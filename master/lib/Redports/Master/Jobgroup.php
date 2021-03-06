<?php

namespace Redports\Master;

/**
 * Group of jobs which belong together somehow.
 *
 * @author     Bernhard Froehlich <decke@bluelife.at>
 * @copyright  2015 Bernhard Froehlich
 * @license    BSD License (2 Clause)
 *
 * @link       https://freebsd.github.io/redports/
 */
class Jobgroup
{
    protected $_db;
    protected $_groupid;

    public function __construct($groupid)
    {
        $this->_db = Config::getDatabaseHandle();
        $this->_groupid = $groupid;
    }

    public function getJobgroupId()
    {
        return $this->_groupid;
    }

    public function addJob($jobid)
    {
        if (!$this->_db->exists('jobs:'.$jobid)) {
            return false;
        }

        if ($this->_db->sAdd('jobgroup:'.$this->getJobgroupId(), $jobid) != 1) {
            return false;
        }

        return true;
    }

    public function countJobs()
    {
        return $this->_db->sSize('jobgroup:'.$this->getJobgroupId());
    }

    public function getJobs()
    {
        return $this->_db->sMembers('jobgroup:'.$this->getJobgroupId());
    }

    public function deleteJobgroup()
    {
        $this->_db->delete('jobgroup:'.$this->getJobgroupId());

        return true;
    }

    public function exists($groupid = null)
    {
        if (is_null($groupid)) {
            $groupid = $this->_groupid;
        }

        return $this->_db->exists('jobgroup:'.$groupid);
    }

    public function getGroupInfo()
    {
        if (!$this->exists()) {
            return false;
        }

        $data = array(
         'groupname' => $this->getJobgroupId(),
         'jobs'      => $jobgroup->getJobs(),
      );

        return $data;
    }
}
