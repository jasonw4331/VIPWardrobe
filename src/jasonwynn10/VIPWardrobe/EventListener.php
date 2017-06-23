<?php
namespace jasonwynn10\VIPWardrobe;

use pocketmine\block\BlockIds;
use pocketmine\event\block\SignChangeEvent;
use pocketmine\event\inventory\InventoryCloseEvent;
use pocketmine\event\inventory\InventoryTransactionEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\inventory\DoubleChestInventory;
use pocketmine\inventory\InventoryType;
use pocketmine\inventory\PlayerInventory;
use pocketmine\item\Armor;
use pocketmine\item\ItemIds;
use pocketmine\Player;
use pocketmine\tile\Sign;

class EventListener implements Listener {
	/** @var main $plugin */
	private $plugin;
	public function __construct(main $plugin) {
		$this->plugin = $plugin;
	}

	/**
	 * @ignoreCancelled true
	 * @priority LOW
	 *
	 * @param SignChangeEvent $ev
	 */
	public function onSign(SignChangeEvent $ev) {
		if(strtolower($ev->getLine(0)) == "[wardrobe]") {
			$player = $ev->getPlayer();
			if(!$player->hasPermission("wardrobe.sign.make")) {
				$ev->setCancelled();
			}
		}
	}

	/**
	 * @ignoreCancelled true
	 * @priority LOW
	 *
	 * @param PlayerInteractEvent $ev
	 */
	public function onTap(PlayerInteractEvent $ev) {
		if($ev->getBlock()->getId() == BlockIds::SIGN_POST or $ev->getBlock()->getId() == BlockIds::WALL_SIGN) {
			$signTile = $ev->getBlock()->getLevel()->getTile($ev->getBlock());
			if($signTile instanceof Sign and strtolower($signTile->getText()[0]) == "[wardrobe]") {
				$player = $ev->getPlayer();
				if(!$player->hasPermission("wardrobe.sign.tap")) {
					return;
				}
				$this->plugin->openWardrobe($player);
			}
		}
		if($ev->getAction() == PlayerInteractEvent::RIGHT_CLICK_BLOCK) {
			if($ev->getItem()->getId() == $this->plugin->getConfig()->get("WindowItem", ItemIds::LEATHER_CAP)) {
				$player = $ev->getPlayer();
				if(!$player->hasPermission("wardrobe.item.tap")) {
					return;
				}
				$this->plugin->openWardrobe($player);
			}
		}
	}

	/**
	 * @ignoreCancelled true
	 * @priority HIGHEST
	 *
	 * @param InventoryTransactionEvent $ev
	 */
	public function onWardrobeTransaction(InventoryTransactionEvent $ev) {
		$bool = false;
		foreach ($ev->getTransaction()->getInventories() as $inventory) {
			if($inventory instanceof DoubleChestInventory and $inventory->getName() == "Wardrobe") {
				$bool = true;
				continue;
			}
			if($bool and $inventory instanceof PlayerInventory) {
                $selection = $ev->getTransaction()->getTransactions()[0]->getTargetItem();
                /** @var Player $player */
                if($selection instanceof Armor and ($player = $inventory->getHolder()) instanceof Player) {
                    $this->plugin->selectArmor($player, $selection, $selection);
                }
            }
		}
	}

	/**
	 * @ignoreCancelled true
	 * @priority MONITOR
	 *
	 * @param InventoryCloseEvent $ev
	 */
	public function onWindowClose(InventoryCloseEvent $ev) {
		if($ev->getInventory()->getType()->getNetworkType() == InventoryType::DOUBLE_CHEST and $ev->getInventory()->getName() == "Wardrobe") {
			$this->plugin->applyArmor($ev->getPlayer());
		}
	}
}
