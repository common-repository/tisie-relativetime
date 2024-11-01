<?php
/**
 * [TiSiE] RelativeTime
 *
 * Template tags definitions
 *
 * @package Tirt
 * @author Mathias Gelhausen <scripting@tisie.de>
 * @copyright 2011 [TiSiE]
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html   GPLv2
 */


/**
 * Displays the relative post date string
 * 
 * @param int $parts Number of parts to show
 */
function the_date_relative($parts=2)
{
    echo get_the_date_relative($parts);
}

/**
 * Gets the relative post date string.
 * 
 * @param int $parts Number of parts to show
 * @return string
 */
function get_the_date_relative($parts=2)
{
    return tirt_get_string(get_the_date('Y-m-d H:i:s'), $parts);
}

/**
 * Displays the relative modified date string
 * 
 * @param int $parts Number of parts to show
 */
function the_modified_date_relative($parts=2)
{
    echo get_the_modified_date_relative($parts);
}

/**
 * Gets the relative modified date string 
 * 
 * @param int $parts Number of parts to show
 * @return string
 */
function get_the_modified_date_relative($parts=2)
{
    return tirt_get_string(get_the_modified_date('Y-m-d H:i:s'), $parts);
}

/**
 * Displays the relative comment date string
 * 
 * @param int $parts Number of parts to show
 */
function comment_date_relative($parts=2)
{
    echo get_comment_date_relative($parts);
}

/**
 * Gets the relative comment date string
 * 
 * @param int $parts Number of parts to show
 * @return string
 */
function get_comment_date_relative($parts=2)
{
    return tirt_get_string(get_comment_date('Y-m-d H:i:s'), $parts);
}
