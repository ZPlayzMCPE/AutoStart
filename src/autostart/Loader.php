<?php

namespace autostart;

use autostart\task\RestartTask;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Loader extends PluginBase {

	public $resource;

	public function onEnable() {
		@mkdir($this->getDataFolder());
		$this->saveResource("resource.yml");
		$this->resource = new Config($this->getDataFolder() . "resource.yml", Config::YAML);
		$this->getServer()->getLogger()->notice("AutoRestart was enabled by David!");
		$this->getServer()->getLogger()->notice("GitHub: https://github.com/diamondgamermcpe");
		$this->getServer()->getLogger()->notice("Twitter: https://twitter.com/DavidGamingzz");
		$this->getServer()->getScheduler()->scheduleRepeatingTask(new RestartTask($this), 60*20);
    }
}
