<?php
	namespace alirezap30web\ShortUrl;

    use alirezap30web\ShortUrl\Drivers\LocalDriver;

    class DriverFactory
	{
		/**
		 * @param string $driver
		 *
		 * @return LocalDriver
		 */
		public function make (string $driver)
		{
			switch ($driver) {
				case "local":
					return new LocalDriver;
				default:
					return new LocalDriver;
			}
		}
	}