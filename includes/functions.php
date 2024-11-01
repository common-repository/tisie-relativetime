<?php
/**
 * [TiSiE] RelativeTime
 *
 * Helper functions
 *
 * @package Tirt
 * @author Mathias Gelhausen <scripting@tisie.de>
 * @copyright 2011 [TiSiE]
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html   GPLv2
 */


/**
 * Gets the date parts as array
 * 
 * @param string $date Date string (mysql DateTime format)
 * @return array|WP_Error
 */
function tirt_get_date_array($date=null)
{
    if (null === $date) {
        $date = current_time('mysql');
    }
    
    if (!preg_match('~^(\d{4}-\d\d-\d\d)(?: (\d\d:\d\d:\d\d))?$~', $date, $match)) {
        return new WP_Error(
            'invalid_date', 
            __('The provided date must be in the format "YYYY-MM-DD HH:II:SS"', 'tirt'), 
            $date
        );
    }      
    $datePart = $match[1];
    $timePart = isset($match[2]) ? $match[2] : '00:00:00';

    list($year, $month, $day) = explode('-', $datePart, 3);
    list($hour, $minute, $second) = explode(':', $timePart, 3);
    
    return array(
        'year' => intval($year),
        'month' => intval($month),
        'day' => intval($day),
        'hour' => intval($hour),
        'minute' => intval($minute),
        'second' => intval($second)
    );
}

/**
 * Gets the date difference as array
 * 
 * @param string $date Date string (mysql DateTime format)
 * @return array|WP_Error Difference to current date or WP_Error object. 
 * @uses tirt_get_date_array()
 */
function tirt_get_array($date)
{
    global $tirt_units;
    
    $start = tirt_get_date_array($date);
    $stop = tirt_get_date_array();
    
    // If $date has an invalid format, tirt_get_date_array returns a WP_Error object.
    // and we pass it along.
    if (is_wp_error($start)) {
        return $start;
    }   
    
    $values = array();
    $increment = 0;   
    
    /*
     * Nedded for the calculation.
     * Format is: unit => shift value
     * This is from second to year, because the algorithm might
     * need to increment the next "bigger" part. 
     */ 
    $tirt_units = array(
        'second' => 60,
        'minute' => 60,
        'hour' => 24,  
        'day' => array(31, -1, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31),
        'week' => false,
        'month' => 12,  
        'year' => false 
    );
    
    foreach ($tirt_units as $part => $shift) {
        if ('week'== $part) {
            // week is not in $values, so need to add it.
            $weeks = 0; 
            if ($values['day'] >= 7) {
                $weeks = floor($values['day'] / 7);
                $values['day'] -= $weeks * 7;
            }
            $values['week'] = $weeks;
            continue;
        } 

        if ($start[$part] + $increment > $stop[$part]) {
            // need to shift the start part (like in written subtraction)
            if ('day' == $part) {
              // day is special, because the shift value vary from month to month.
              $shift = $shift[$start['month'] - 1] == -1 // February depends on leap year.
                     ? ($start['year'] % 4 == 0 && ($start['year'] % 100 != 0 || $start['year'] % 400 == 0)
                        ? 29 : 28)
                     : $shift[$start['month'] -1];
            }
            $values[$part] = ($stop[$part] + $shift) - ($start[$part] + $increment);
            $increment = 1; // Must take the increment from the shifting to the next part.
        } else {
            $values[$part] = $stop[$part] - ($start[$part] + $increment);
            $increment = 0; // No shifting, no incrementing!
        }
        
    } 
    
    $values = array_reverse($values); // bring values in "right" order.
    $values = apply_filters('tirt_get_array', $values);
    if (!is_array($values) || array_keys($values) !== array_reverse(array_keys($tirt_units))) {
        return new WP_Error(
            'invalid_value', 
            __('The return value of a filter which filters "tirt_get_array" '
               . 'must be an array with the same keys as the input array', 'tirt'),
            $values
        );
    }
    return $values;
}

/**
 * Gets a relative time string
 * 
 * @param string $date Date string (mysql DateTime format)
 * @return string
 * @uses tirt_get_array()
 */
function tirt_get_string($date, $parts=2)
{
    $values = tirt_get_array($date);
    
    if (is_wp_error($values)) {
        return '[TiSiE] RelativeTime || ERROR: ' . implode(', ', $values->get_error_messages());
    }
    
    $i = 0; $outputChunks = array();
    foreach ($values as $unit => $value) {
        if (0 != $value) {
            $outputChunks[] = $value . ' ' . __($unit . ($value != 1 ? 's' : ''), 'tirt');
            $i += 1;
        }
        if ($i == $parts || ($i && 'week' != $unit && 0 == $value)) {
            // if there are already $parts parts or a value after the first part with a non-zero
            // value is "0". Week is handled special, because strings like
            // "3 months and 4 days" also makes sense.
            break;
        }
    }
    $output = '';
    if ($lastChunk = array_pop($outputChunks)) {
        if (count($outputChunks)) {
            $output .= implode(', ', $outputChunks) . ' ' . __('and', 'tirt') . ' ';
        }
        $output .= $lastChunk;
    } else {
        $ouput .= __('less than a second', 'tirt');
    }
    
    return apply_filters('tirt_get_string', $output);
}

/**
 * Shortcode Handler for "reltime".
 * 
 * @since 0.3
 * @param array $attributes Raw attributes passed by wordpress
 * @param string $content Enclosed content of this tag
 * @param string $tag Tag-Name passed by wordpress
 * @return string
 * @uses tirt_get_string()
 */
function tirt_shortcode($attributes, $content, $tag)
{
    extract(shortcode_atts(array(
        'format' => '',
        'parts' => 2
    ), $attributes));
    
    $time = strtotime($content);
    if (false === $time || -1 == $time) { // in PHP < 5.1 strtotime returns -1 on faileure
        return $content;
    }
    $date = date('Y-m-d H:i:s', $time);
    $title = empty($format)
           ? addslashes($content)
           : addslashes(date_i18n($format, $time));
    return '<span class="tirt_reltime" title="' . $title . '">'
           . tirt_get_string($date, $parts)
           . '</span>';
}