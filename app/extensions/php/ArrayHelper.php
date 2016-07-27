<?php

namespace Acme\php;

/*
 * All Stuff of Array Helper Functions should be placed here ..
 */
class ArrayHelper {

	/*
	 * Search if $value is in $array field $index
	 *
	 * @param: array: array to search
	 * @param: array: the array[].index field to search in
	 * @param: array: search pattern
	 * @return: the found element, otherwise null
	 *
	 * @author: Torsten Schmidt
	 */
	public static function objArraySearch($array, $index, $value)
	{
		foreach($array as $arrayInf) {
			if($arrayInf->{$index} == $value) {
				return $arrayInf;
			}
		}
		return null;
	}


	/*
	 * Device all entrys of an Array by $div
	 *
	 * @param $array: The Array to split
	 * @param $div: device by $div
	 * @return: The devided array
	 *
	 * @author: Torsten Schmidt
	 */
	public static function ArrayDiv($array, $div=10)
	{
		$ret = [];

		foreach ($array as $a)
		{
			array_push($ret, $a/$div);
		}

		return $ret;
	}


	/*
	 * return the rotated array ($a)
	 * Example: [1,2,3,4] -> [4,1,2,3]
	 *
	 * @param $a: The Array to rotate
	 * @return: The rotated/shifted array
	 *
	 * @author: Torsten Schmidt
	 */
	public static function array_rotate($a)
	{
		return array_merge(array(array_pop($a)), $a);
	}


	/*
	 * return nested array depth
	 *
	 * @param $a: The Array to check
	 * @return: the nested array depth as int
	 *
	 * @author: Torsten Schmidt
	 */
	public static function array_depth(array $array)
	{
		$max_depth = 1;

		foreach ($array as $value) {
			if (is_array($value)) {
				$depth = ArrayHelper::array_depth($value) + 1;

				if ($depth > $max_depth) {
					$max_depth = $depth;
				}
			}
		}

		return $max_depth;
	}


	/*
	 * return the changed array key from $old_key to $new_key in $array
	 *
	 * NOTE: This function holds the same order than in $array
	 * SEE: http://stackoverflow.com/questions/240660/in-php-how-do-you-change-the-key-of-an-array-element
	 *
	 * @param $array: the given array
	 * @param $old_key: old key value to change
	 * @param $new_key: the new key value
	 * @return: the key changed $array,
	 *
	 * @author: Torsten Schmidt
	 */
	public static function change_array_key($array, $old_key, $new_key)
	{
		if(!is_array($array))
			return $array;

		if(!array_key_exists($old_key, $array))
			return $array;

		$key_pos = array_search($old_key, array_keys($array));
		$arr_before = array_slice($array, 0, $key_pos);
		$arr_after = array_slice($array, $key_pos + 1);
		$arr_renamed = array($new_key => $array[$old_key]);

		return $arr_before + $arr_renamed + $arr_after;
	}

}