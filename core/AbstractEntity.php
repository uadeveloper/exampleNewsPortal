<?php

declare(strict_types=1);

namespace SiteCore;

/**
 * Class AbstractEntity
 * @package SiteCore
 */
class AbstractEntity
{

    /**
     * @param array $data
     */
    public function loadByArray(array $data)
    {
        foreach ($data as $key => $item) {
            #if (isset($this->{$key})) {
                $this->{$key} = $item;
            #}
        }
    }

}