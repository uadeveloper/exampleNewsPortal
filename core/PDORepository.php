<?php

declare(strict_types=1);

namespace SiteCore;

abstract class PDORepository
{

    protected $db;

    final public function __construct($db)
    {
        $this->db = $db;
    }

}
