<?php

namespace AnvilRepair;

use pocketmine\Server;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\block\Block;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\Item;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\event\player\PlayerToggleSneakEvent;

class Main extends PluginBase implements Listener{
	private $tap = [];
	private $sneak;
	
	public function onEnable(){
		@mkdir($this->getDataFolder());
		$this->saveDefaultConfig();
		$this->cfg = $this->getConfig()->getAll();
		$this->economy = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}
	
	public function getEconomy(){
		return $this->economy;
	}
	
	public function onSneak(PlayerToggleSneakEvent $ev){
		if($ev->isSneaking()){
			$this->sneak = true;
		}else{
			$this->sneak = false;
		}
	}
	public function onTap(PlayerInteractEvent $ev){
		$player = $ev->getPlayer();
		$block = $ev->getBlock();
		switch($block->getId()){
			case Block::ANVIL:
			 $time = time();
			 $user = strtolower($player->getName());
			 if($this->sneak != false){
			 	if(isset($this->tap[$user]) and $time - $this->tap[$user] < 1){
			 		$this->repararItem($player);
			 		unset($this->tap[$user]);
			 	}else{
			 		$this->tap[$user] = $time;
			 		$player->sendMessage($this->cfg['msg-tap'].$this->cfg['reparar-money-cost']);
			 		return;
			 	}
			 }
			break;
		}
	}
	
	public function repararItem(Player $player){
		$ids = [298,299,300,301,302,303,304,305,306,307,308,309,310, 311,312,313,314,315,316,317,256,257,258,267,292,268,269,270,271,290,272,273,274,275,291,276,277,278,279,293,283,284,285,286,294,261];
		$money = $this->getEconomy()->myMoney($player);
		$reCost = $this->cfg['reparar-money-cost'];
		$hand = $player->getInventory()->getItemInHand();
		$cost = $reCost - $money;
		
		if($money < $reCost){
			$player->sendMessage($this->cfg['msg-sem-money'].$cost);
			return;
		}
		if(!in_array($hand->getId(), $ids)){
			$player->sendMessage($this->cfg['msg-item-nao-permitido']);
			return;
		}
		$item = Item::get($hand->getId(), 0, $hand->getCount());
		if($hand->hasCustomName()){
			$item->setCustomName($hand->getCustomName());
		}
		if($hand->hasEnchantments()){
			foreach($hand->getEnchantments() as $enchs){
				$eid = $enchs->getId();
				$elvl = $enchs->getLevel();
				$ench = Enchantment::getEnchantment($eid);
				$ench->setLevel($elvl);
				$item->addEnchantment($ench);
			}
		}
		$this->getEconomy()->reduceMoney($player, $reCost);
		$player->getInventory()->setItemInHand($item);
		$player->sendMessage($this->cfg['msg-sucesso']);
	}
}