--TEST--
CacheCore::timestamp

--FILE--
<?php
	require_once dirname(__FILE__) . '/../cachecore.class.php';
	$cache = new CacheCore('test', './cache', 60);
	var_dump($cache->timestamp);
?>

--EXPECT--
NULL
