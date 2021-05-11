<?php

declare(strict_types=1);

namespace SiteCore;

/**
 * Class PDORepository
 * @package SiteCore
 */
abstract class PDORepository
{

    protected $db;

    /**
     * PDORepository constructor.
     * @param $db
     */
    final public function __construct($db)
    {
        $this->db = $db;
    }

}
