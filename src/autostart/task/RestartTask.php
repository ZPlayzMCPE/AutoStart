<?php

namespace autostart\task;

use autostart\Loader;

use pocketmine\scheduler\PluginTask;
use pocketmine\Server;
use pocketmine\plugin\Plugin;

class RestartTask extends PluginTask {

	public $minutes = 0;

	public function __construct(Plugin $plugin){
		$this->plugin = $plugin;
		parent::__construct($plugin);

	}

	public function onRun($tick) {
		$time = $this->plugin->resource->get("Time");
		$this->minutes++;
		$restarttime = $time - $this->minutes;
		foreach($this->plugin->getServer()->getOnlinePlayers() as $player) {
			$popup = str_replace("{time}", $restarttime, $this->plugin->resource->get("Popup"));
			$player->sendPopup($popup);
		}
		if($restarttime == 1) {
			foreach($this->plugin->getServer()->getOnlinePlayers() as $player) {
				$message = str_replace("{time}", $restarttime, $this->plugin->resource->get("Popup"));
				$player->addTitle($message, "", 60, 60, 60);
			}
		}
		if($restarttime == 0) {
			foreach($this->plugin->getServer()->getOnlinePlayers() as $player) {
				$reason = $this->plugin->resource->get("Kick Message");
				$player->kick($reason);
			}
			$this->plugin->getServer()->shutdown();
		}
	}
}
