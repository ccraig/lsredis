<?

	class LsRedis_Module extends Core_ModuleBase
	{
		/**
		* Creates the module information object
		* @return Core_ModuleInfo
		*/
		protected function createModuleInfo()
		{
			return new Core_ModuleInfo(
				"Redis caching for Lemonstand",
				"Allows for Redis based caching to be added to Lemonstand via the Predis library",
				"Chuck Does I.T.",
				"http://www.chuckdoesit.com");
		}
	}

?>
