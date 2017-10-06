<?php
namespace FaigerSYS\superBAR;

use pocketmine\scheduler\PluginTask;

class LoaderTask extends PluginTask {
	
	private $loader;
	
	public function __construct(superBAR $plugin, Loader $loader) {
		$this->loader = $loader;
		parent::__construct($plugin);
	}
	
	public function onRun(int $currentTick) {
		$this->loader->onEnable($this->getOwner());
	}
	
}