<?php

/**
 * Description of FixdigitalCrmUtils
 *
 * @author satuskam
 */
class FixdigitalCrmUtils
{
    public function isCustomBlog()
    {
        $result = true;

        if (is_multisite() && get_site_option('isWebGroupUcoSite')) {
            $result = false;
        }

        return $result;
    }
}
