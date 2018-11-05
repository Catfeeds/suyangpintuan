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
 */
function smarty_modifier_mprice($string, $type='int') {
	if($string==false)return '';
    if(strpos($string,'.')){
        $arr = explode('.', $string);
		return $type=='int'?$arr[0]:$arr[1];
    }else{
		return $type=='int'?$string:'00';
	}
} 

?>