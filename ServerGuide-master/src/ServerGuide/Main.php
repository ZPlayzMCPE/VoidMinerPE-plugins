<?php
namespace ServerGuide;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\item\Item;
use pocketmine\utils\Config;
use pocketmine\inventory\Inventory;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\player\PlayerInteractEvent;

/*
 * Developed by TheAz928(Az928)
 * Editing or copying isn't allowed
 * Leet.cc or play.mc cannot use this plugin
 * Twitter @TheAz928
 * Github team: Github.com/ShiningMC
 *
  */

class Main extends PluginBase implements Listener{
	
	public $prefix;
	const WARNING = "§e§l[!]§r ";
	
	public function onEnable(){
		  $this->saveDefaultConfig();
		  $this->cfg = $this->getConfig()->getAll();
		  $this->prefix = $this->cfg["prefix"];
		  $this->getServer()->getPluginManager()->registerEvents($this, $this);
		  $this->getLogger()->info("§aServerGuide has been enabled!");
		}
		
	/* @param Translate vars */
	
	/* isn't done yet */
	
	public function translate(String $txt){
		  $string = $txt;
	     $string = str_replace("\n", "\n", $string);
        $string = str_replace("{max_online}", $this->getServer()->getMaxPlayers(), $string);
        $string = str_replace("{online}", count($this->getServer()->getOnlinePlayers()), $string);
	return $string;
	}
		
	/* @param getGuideItem */
	
	 public function getGuideItem(){
	      $data = $this->cfg["guide.item"];
	      $tmp = explode(":", $data);
	      $item = Item::get($tmp[0], $tmp[1], 1);
	      $item->setCustomName($this->translate($this->cfg["guide.item.name"]));
	   return $item;
		}
		
	 /* @param removeExtraGuideItem */
	
	 public function cleanup(Player $player){
		   $data = $this->cfg["guide.item"];
	       $tmp = explode(":", $data);
	       if($player->getInventory()->contains(Item::get($tmp[0], $tmp[1], 2))){
		      $player->getInventory()->removeItem(Item::get($tmp[0], $tmp[1], 634));
		      $player->getInventory()->addItem($this->getGuideItem());
		    }
		}
		
	 /* @param sendGuide */
	
    public function sendGuide(Player $player){
	      $player->sendMessage($this->cfg["prefix"]);
	    if(isset($this->cfg["guide.list"])){
          foreach($this->cfg["guide.list"] as $help){
	           $return = "§r§6".$help."§r";
	           $player->sendMessage($this->translate($return));
	      }
	   }
	}
	
	 /* @param analize player */
	
	 public function analyze(Player $player){
	      $inv = $player->getInventory();
	      $data = $this->cfg["guide.item"];
	      $tmp = explode(":", $data);
	      if($player instanceof Player){
		    if($inv->contains(Item::get($tmp[0], $tmp[1], 1))){
			    $this->cleanup($player);
		     }else{
		      $inv->addItem($this->getGuideItem());
			 }
	     }
	  }
	 public function onRespawn(PlayerRespawnEvent $event){
	      $player = $event->getPlayer();
	      $this->analyze($player);
		}
	  public function analyzeJoin(PlayerJoinEvent $event){
	      $player = $event->getPlayer();
	      $this->analyze($player);
	      //$player->getInventory()->setItemInHand($player->getInventory()->getItem($this->getGuideItem()));
		}
	 public function onInteract(PlayerInteractEvent $event){
	      $player = $event->getPlayer();
	      $eitem = $event->getItem();
	      $data = $this->cfg["guide.item"];
	      $tmp = explode(":", $data);
	      $this->analyze($player);
	      if($eitem->getId() == $tmp[0] && $eitem->getDamage() == $tmp[1] && $eitem->getCustomName() == $this->cfg["guide.item.name"]){
		     $this->sendGuide($player);
             }
		}
	  public function onDrop(PlayerDropItemEvent $event){
	      $player = $event->getPlayer();
	      $item = $event->getItem();
	      $data = $this->cfg["guide.item"];
	      $tmp = explode(":", $data);
	      if($item->getId() == $tmp[0] && $item->getDamage() == $tmp[1] && $item->getCustomName() == $this->cfg["guide.item.name"]){
		     $player->sendMessage(self::WARNING."§cYou can't drop help item!");
		     $event->setCancelled();
		     $this->cleanup($player);
          }
     }
}

	
	
	
