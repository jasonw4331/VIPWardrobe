<?php
namespace jasonwynn10\VIPWardrobe;

use pocketmine\scheduler\PluginTask;

class RainbowChangeTask extends PluginTask {
	public function onRun($currentTick) {
		$this->getOwner()->updateRainbowArmor();
	}
}