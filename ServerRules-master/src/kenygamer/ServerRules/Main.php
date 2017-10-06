<?php

/*
 * ServerRules plugin for PocketMine-MP
 * Copyright (C) 2017 Kevin Andrews <https://github.com/kenygamer/ServerRules>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
*/

namespace kenygamer\ServerRules;

use pocketmine\Player;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;

class Main extends PluginBase implements Listener {
	
	/**
	 * onEnable()
	 *
	 * Plugin enable
	 *
	 * @return void
	 */
	public function onEnable() {
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->getLogger()->info("§aEnabling " . $this->getDescription()->getFullName() . "...");
		$this->saveDefaultConfig();
	}
	
	 /**
	  * onDisable()
	  *
	  * Plugin disable
	  *
	  * @return void
	  */
	 public function onDisable() {
                $this->getLogger()->info("§cDisabling " . $this->getDescription()->getFullName() . "...");
	 }
	
	/**
	 * onCommand()
	 *
	 * Plugin commands
	 *
	 * @param CommandSender $sender
	 * @param Command $command
	 * @param string $label
	 * @param array @args
	 */
	public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool {
        $cmd = strtolower($command->getName());
        switch ($cmd){
            case "rules":
                if (!($sender instanceof Player)) {
                    $sender->sendMessage("§2--------§4[ServerRules]§2-------");
			foreach($this->getConfig()->get("rules") as $rule) {
				$sender->sendMessage($rule);
			}
                    return true;
                }
                $player = $this->getServer()->getPlayer($sender->getName());
                if ($player->hasPermission("command.rules")) {
                    $sender->sendMessage("§2--------§4[ServerRules]§2--------");
			foreach($this->getConfig()->get("rules") as $rule) {
				$sender->sendMessage($rule);
			}
                    return true;
                }
                break;
            }
        }
    }
