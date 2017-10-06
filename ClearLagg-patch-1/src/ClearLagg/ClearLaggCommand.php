<?php

namespace ClearLagg;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\plugin\Plugin;

class ClearLaggCommand extends Command implements PluginIdentifiableCommand {

	/** @var Loader */
	private $plugin;

	public function __construct(Loader $plugin) {
		parent::__construct("clearlagg", "Clear the lag!", "/clearlagg <check/clear/killmobs/clearall>", ["lagg"]);
		$this->setPermission("clearlagg.command.clearlagg");
		$this->plugin = $plugin;
	}

	/**
	 * @return Plugin|Loader
	 */
	public function getPlugin(): Plugin {
		return $this->plugin;
	}

	public function execute(CommandSender $sender, string $alias, array $args): bool {
		if(!$this->testPermission($sender)) {
			return false;
		}
		if(isset($args[0])) {
			switch($args[0]) {
				case "clear":
					$sender->sendMessage("Removed " . $this->getPlugin()->removeEntities() . " entities.");
					return true;

				case "check":
				case "count":
					$c = $this->getPlugin()->getEntityCount();
					$sender->sendMessage("There are " . $c[0] . " players, " . $c[1] . " mobs, and " . $c[2] . " entities.");
					return true;

				case "reload":
					// TODO
					return true;

				case "killmobs":
					$sender->sendMessage("Removed " . $this->getPlugin()->removeMobs() . " mobs.");
					return true;

				case "clearall":
					$sender->sendMessage("Removed " . ($d = $this->getPlugin()->removeMobs()) . " mob" . ($d == 1 ? "" : "s") . " and " . ($d = $this->getPlugin()->removeEntities()) . " entit" . ($d == 1 ? "y" : "ies") . ".");
					return true;

				case "area":
					// TODO
					return true;

				case "unloadchunks":
					// TODO
					return true;

				case "chunk":
					// TODO
					return true;

				case "tpchunk":
					// TODO
					return true;

				default:
					return false;
			}
		}
		return false;
	}

}
