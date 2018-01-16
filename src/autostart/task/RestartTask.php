<?php

namespace autostart\task;

use autostart\Loader;

use pocketmine\scheduler\PluginTask;
use pocketmine\Server;
use pocketmine\plugin\Plugin;

class RestartTask extends PluginTask {

	public $seconds = 0;

	public function __construct(Plugin $plugin){
		$this->plugin = $plugin;
		parent::__construct($plugin);
	}

	public function onRun($tick) {
		$time = $this->plugin->resource->get("Time")*60;
		$this->seconds++;
		$restartTime = $time - $this->seconds;
		$currentTime = intdiv($restartTime, 60);
		foreach($this->plugin->getServer()->getOnlinePlayers() as $player) {
			$popup = str_replace("{time}", $currentTime, $this->plugin->resource->get("Popup"));
			$player->sendPopup($popup);
		}
		if($restartTime/60 === 1) {
			foreach($this->plugin->getServer()->getOnlinePlayers() as $player) {
				$message = str_replace("{time}", $currentTime, $this->plugin->resource->get("Popup"));
				$title = str_replace("minutes", "minute", $message);
				$player->addTitle($title, "", 20, 40, 20);
			}
		}
		if($restartTime <= 60) {
			foreach($this->plugin->getServer()->getOnlinePlayers() as $player) {
				$message = str_replace("{time}", $restartTime, $this->plugin->resource->get("Popup"));
				$popup = str_replace("minutes", "", $message);
				$player->sendPopup($popup);
			}
		}
		if($restartTime === 0) {
			foreach($this->plugin->getServer()->getOnlinePlayers() as $player) {
				$reason = $this->plugin->resource->get("Kick Message");
				$player->kick($reason);
			}
			$this->plugin->getServer()->shutdown();
		}
	}
}
