<?php
	
	namespace alirezap30web\ShortUrl;
	
	use alirezap30web\ShortUrl\Drivers\BaseDriver;

	class Shorturl
	{
		protected $factory;
		protected $driver;
		private $driver_instance;

		public function __construct (DriverFactory $factory)
		{
			$this->factory = $factory;
		}
		
		/**
		 * @param string $driver
		 *
		 * @return Shorturl
		 */
		public function onDriver (string $driver)
		{
			$this->driver = $driver;
			$this->setDriverInstance();
			return $this;
		}

		/**
		 * @return void
		 */
		private function setDriverInstance ()
		{
			$this->driver_instance = $this->factory->make($this->getDriver());
		}
		
		/**
		 *
		 * Get name of current driver in use
		 *
		 * @return string
		 */
		public function getDriver (): string
		{
			return $this->driver ?: config("shorturl.drivers.default");
		}
		
		public function __call ($method, $args)
		{
			return call_user_func_array(array ($this->getDriverInstance(), $method), $args);
		}
		
		private function getDriverInstance ()
		{
			if (!$this->driver_instance)
				$this->setDriverInstance();
			
			return $this->driver_instance;
		}
	}