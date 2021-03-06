<?php

namespace autostart\task;

use autostart\Loader;

use pocketmine\scheduler\Task;
use pocketmine\Server;
use pocketmine\plugin\Plugin;
use pocketmine\event\server\ServerCommandEvent;

class RestartTask extends PluginTask {

	public $seconds = 0;

	public function __construct(Plugin $plugin){
		$this->plugin = $plugin;
		parent::__construct($plugin);
	}

	public function onRun(int $tick): void {
		$time = $this->plugin->resource->get("Time") * 60;
		$this->seconds++;
		$restartTime = $time - $this->seconds;
		$currentTime = intdiv($restartTime, 60);
		$currentSeconds = $restartTime - $currentTime * 60;
		foreach($this->plugin->getServer()->getOnlinePlayers() as $player) {
			$popup = str_replace("{time}", "$currentTime minutes $currentSeconds seconds", $this->plugin->resource->get("Popup"));
			$player->sendPopup($popup);
		}
		if($currentTime === 1) {
			foreach($this->plugin->getServer()->getOnlinePlayers() as $player) {
				$popup = str_replace("{time}", "$currentTime minute $currentSeconds seconds", $this->plugin->resource->get("Popup"));
				$player->sendPopup($popup);
			}
		}
		if($restartTime/60 === 1) {
			foreach($this->plugin->getServer()->getOnlinePlayers() as $player) {
				$title = str_replace("{time}", "$currentTime minute", $this->plugin->resource->get("Popup"));
				$player->addTitle($title, "", 20, 40, 20);
			}
		}
		if($restartTime <= 59) {
			foreach($this->plugin->getServer()->getOnlinePlayers() as $player) {
				$popup = str_replace("{time}", "$restartTime seconds", $this->plugin->resource->get("Popup"));
				$player->sendPopup($popup);
			}
		}
		if($restartTime <= 0) {
		foreach($this->plugin->getServer()->getOnlinePlayers() as $player) {
				$reason = $this->plugin->resource->get("Kick Message");
				$player->kick($reason);
			}
			$this->plugin->getServer()->shutdown();
		}
	}
}
