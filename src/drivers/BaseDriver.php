<?php
	namespace alirezap30web\ShortUrl\Drivers;
	
	interface BaseDriver
	{
		/** @noinspection PhpLanguageLevelInspection */
		
		/**
		 * @param string $url
		 *
		 * @return mixed
		 */
		function shorten (string $url) :string;
		
		/**
		 * @param string $url
		 *
		 * @return mixed
		 */
		function expand (string $url) :string;
	}