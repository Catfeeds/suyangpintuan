<?php
/**
 * Smarty plugin
 *
 * @package Smarty
 * @subpackage PluginsModifier
 */
 
/**
 * Smarty truncate modifier plugin
 * 
 * Type:     modifier<br>
 * Name:     truncate<br>
 * Purpose:  Truncate a string to a certain length if necessary,
 *               optionally splitting in the middle of a word, and
 *               appending the $etc string or inserting $etc into the middle.
 * 
 * @link http://smarty.php.net/manual/en/language.modifier.truncate.php truncate (Smarty online manual)
 * @author Monte Ohrt <monte at ohrt dot com> 
 * @param string  $string      input string
 * @param integer $length      length of truncated text
 * @param string  $etc         end string
 * @param boolean $break_words truncate at word boundary
 * @param boolean $middle      truncate in the middle of text
 * @return string truncated string
 */
function smarty_modifier_truncate($string, $length = 80, $etc = '...', $break_words = false, $middle = false) {
    if ($length == 0)
        return '';

    $result   =   '';

    $string   =   html_entity_decode(trim(strip_tags($string)),   ENT_QUOTES,   'UTF-8');
    //$string   =   trim($string);
    $strlen   =   strlen($string);

    for($i   =   0;   (($i   <   $strlen)   &&   ($length   >   0));   $i++)
    {
        if($number   =   strpos(str_pad(decbin(ord(substr($string,   $i,   1))),   8,   '0',   STR_PAD_LEFT),   '0'))
        {
            if($length   <   1.0)
            {
                break;
            }

            $result   .=   substr($string,   $i,   $number);

            $length   -=   1.0;

            $i   +=   $number   -   1;
        }
        else
        {
            $result   .=   substr($string,   $i,   1);

            $length   -=   0.5;
        }
    }

    $result   =   htmlspecialchars($result,   ENT_QUOTES,   'UTF-8');

    if($i   <   $strlen)
    {
        $result   .=   $etc;
    }

    return   $result;
} 

?>