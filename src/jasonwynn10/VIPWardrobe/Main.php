<?php
namespace jasonwynn10\VIPWardrobe;

use pocketmine\inventory\DoubleChestInventory;
use pocketmine\item\Armor;
use pocketmine\item\Item;
use pocketmine\level\Position;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\tile\Chest;
use pocketmine\tile\Tile;
use pocketmine\utils\TextFormat;

class Main extends PluginBase {
    /** @var Item[][] $menuPages */
	private $menuPages = [];
	/** @var string[][] $selections */
	private $selections = [];

	public function onEnable() {
		$this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
		$this->getLogger()->notice("Plugin by jasonwynn10");
		$this->loadMenuPages(); // TODO finish wardrobe item menus
        $this->getServer()->getScheduler()->scheduleRepeatingTask(new RainbowChangeTask($this), 1);
	}

	private function loadMenuPages() {
		$this->menuPages = [
            [ // Armor
                [
                # All at once specials
				0 => Item::get(Item::FLINT_AND_STEEL)->setCustomName(TextFormat::GREEN."Remove Armor"),
				1 => Item::get(Item::ENCHANT_TABLE)->setCustomName(TextFormat::GREEN."Enchant All"),
                # All at once armour sets
				3 => Item::get(Item::LEATHER)->setCustomName(TextFormat::BLUE."Leather Armor"),
				4 => Item::get(Item::GOLD_INGOT)->setCustomName(TextFormat::GOLD."Gold Armor"),
				5 => Item::get(Item::DIAMOND),
				6 => Item::get(Item::IRON_INGOT),
				7 => Item::get(Item::ANVIL),
                # Helmet Specials
				9 => Item::get(Item::BUCKET,10),
				10 => Item::get(Item::ENCHANTED_BOOK),
				# Helmets
				12 => Item::get(Item::LEATHER_CAP),
				13 => Item::get(Item::GOLD_HELMET),
				14 => Item::get(Item::DIAMOND_HELMET),
				15 => Item::get(Item::IRON_HELMET),
				16 => Item::get(Item::CHAIN_HELMET),
                # Chestplate Specials
				18 => Item::get(Item::BUCKET,10),
				19 => Item::get(Item::ENCHANTED_BOOK),
				# ChestPlates
				21 => Item::get(Item::LEATHER_TUNIC),
				22 => Item::get(Item::GOLD_CHESTPLATE),
				23 => Item::get(Item::DIAMOND_CHESTPLATE),
				24 => Item::get(Item::IRON_CHESTPLATE),
				25 => Item::get(Item::CHAIN_CHESTPLATE),
                # Legging Specials
				27 => Item::get(Item::BUCKET,10),
				28 => Item::get(Item::ENCHANTED_BOOK)->setCustomName(TextFormat::GREEN."Enchant Leggings"),
				# Leggings
				30 => Item::get(Item::LEATHER_PANTS),
				31 => Item::get(Item::GOLD_LEGGINGS),
				32 => Item::get(Item::DIAMOND_LEGGINGS),
				33 => Item::get(Item::IRON_LEGGINGS),
				34 => Item::get(Item::CHAIN_LEGGINGS),
                # Boot Specials
				36 => Item::get(Item::BUCKET,10),
				37 => Item::get(Item::ENCHANTED_BOOK),
				# Boots
				39 => Item::get(Item::LEATHER_BOOTS),
				40 => Item::get(Item::GOLD_BOOTS),
				41 => Item::get(Item::DIAMOND_BOOTS),
				42 => Item::get(Item::IRON_BOOTS),
				43 => Item::get(Item::CHAIN_BOOTS),
				# Page selection
                44 => Item::get(Item::ARROW)->setCustomName(TextFormat::GOLD."Colored Armor Page 1 ->"),
				45 => Item::get(Item::WOOL,5),
				46 => Item::get(Item::WOOL,7),
				47 => Item::get(Item::WOOL,7),
				48 => Item::get(Item::WOOL,7),
				49 => Item::get(Item::WOOL,7),
				50 => Item::get(Item::WOOL,7),
				51 => Item::get(Item::WOOL,7),
				52 => Item::get(Item::WOOL,7),
				53 => Item::get(Item::WOOL,7),
				54 => Item::get(Item::WOOL,14)
			],
                [
                    # Page selection
                    44 => Item::get(Item::ARROW)->setCustomName(TextFormat::GOLD."Colored Armor Page 1 ->"),
                    45 => Item::get(Item::WOOL,5),
                    46 => Item::get(Item::WOOL,7),
                    47 => Item::get(Item::WOOL,7),
                    48 => Item::get(Item::WOOL,7),
                    49 => Item::get(Item::WOOL,7),
                    50 => Item::get(Item::WOOL,7),
                    51 => Item::get(Item::WOOL,7),
                    52 => Item::get(Item::WOOL,7),
                    53 => Item::get(Item::WOOL,7),
                    54 => Item::get(Item::WOOL,14)
                ]
            ],
            [ // Hats
                [
                # Page selection
                35 => Item::get(Item::ARROW)->setCustomName(TextFormat::GOLD.""),
                44 => Item::get(Item::ARROW)->setCustomName(TextFormat::GOLD."Colored Armor Page 2 ->"),
                45 => Item::get(Item::WOOL,7)->setCustomName(TextFormat::GOLD."Wardrobe"),
                46 => Item::get(Item::WOOL,5),
                47 => Item::get(Item::WOOL,7),
                48 => Item::get(Item::WOOL,7),
                49 => Item::get(Item::WOOL,7),
                50 => Item::get(Item::WOOL,7),
                51 => Item::get(Item::WOOL,7),
                52 => Item::get(Item::WOOL,7),
                53 => Item::get(Item::WOOL,7),
                54 => Item::get(Item::WOOL,14)
            ],
                [
                    # Page selection
                    45 => Item::get(Item::WOOL,7),
                    46 => Item::get(Item::WOOL,7),
                    47 => Item::get(Item::WOOL,5),
                    48 => Item::get(Item::WOOL,7),
                    49 => Item::get(Item::WOOL,7),
                    50 => Item::get(Item::WOOL,7),
                    51 => Item::get(Item::WOOL,7),
                    52 => Item::get(Item::WOOL,7),
                    53 => Item::get(Item::WOOL,7),
                    54 => Item::get(Item::WOOL,14)
                ],
                [
                    # Page selection
                    45 => Item::get(Item::WOOL,7),
                    46 => Item::get(Item::WOOL,7),
                    47 => Item::get(Item::WOOL,7),
                    48 => Item::get(Item::WOOL,5),
                    49 => Item::get(Item::WOOL,7),
                    50 => Item::get(Item::WOOL,7),
                    51 => Item::get(Item::WOOL,7),
                    52 => Item::get(Item::WOOL,7),
                    53 => Item::get(Item::WOOL,7),
                    54 => Item::get(Item::WOOL,14)
                ],
                [
                    # Page selection
                    45 => Item::get(Item::WOOL,7),
                    46 => Item::get(Item::WOOL,7),
                    47 => Item::get(Item::WOOL,7),
                    48 => Item::get(Item::WOOL,7),
                    49 => Item::get(Item::WOOL,5),
                    50 => Item::get(Item::WOOL,7),
                    51 => Item::get(Item::WOOL,7),
                    52 => Item::get(Item::WOOL,7),
                    53 => Item::get(Item::WOOL,7),
                    54 => Item::get(Item::WOOL,14)
                ],
                [
                    # Page selection
                    45 => Item::get(Item::WOOL,7),
                    46 => Item::get(Item::WOOL,7),
                    47 => Item::get(Item::WOOL,7),
                    48 => Item::get(Item::WOOL,7),
                    49 => Item::get(Item::WOOL,7),
                    50 => Item::get(Item::WOOL,5),
                    51 => Item::get(Item::WOOL,7),
                    52 => Item::get(Item::WOOL,7),
                    53 => Item::get(Item::WOOL,7),
                    54 => Item::get(Item::WOOL,14)
                ],
                [
                    # Page selection
                    45 => Item::get(Item::WOOL,7),
                    46 => Item::get(Item::WOOL,7),
                    47 => Item::get(Item::WOOL,7),
                    48 => Item::get(Item::WOOL,7),
                    49 => Item::get(Item::WOOL,7),
                    50 => Item::get(Item::WOOL,7),
                    51 => Item::get(Item::WOOL,5),
                    52 => Item::get(Item::WOOL,7),
                    53 => Item::get(Item::WOOL,7),
                    54 => Item::get(Item::WOOL,14)
                ],
                [
                    # Page selection
                    45 => Item::get(Item::WOOL,7),
                    46 => Item::get(Item::WOOL,7),
                    47 => Item::get(Item::WOOL,7),
                    48 => Item::get(Item::WOOL,7),
                    49 => Item::get(Item::WOOL,7),
                    50 => Item::get(Item::WOOL,7),
                    51 => Item::get(Item::WOOL,5),
                    52 => Item::get(Item::WOOL,7),
                    53 => Item::get(Item::WOOL,7),
                    54 => Item::get(Item::WOOL,14)
                ],
                [
                    # Page selection
                    45 => Item::get(Item::WOOL,7),
                    46 => Item::get(Item::WOOL,57),
                    47 => Item::get(Item::WOOL,7),
                    48 => Item::get(Item::WOOL,7),
                    49 => Item::get(Item::WOOL,7),
                    50 => Item::get(Item::WOOL,7),
                    51 => Item::get(Item::WOOL,7),
                    52 => Item::get(Item::WOOL,5),
                    53 => Item::get(Item::WOOL,7),
                    54 => Item::get(Item::WOOL,14)
                ],
                [
                    # Page selection
                    45 => Item::get(Item::WOOL,7),
                    46 => Item::get(Item::WOOL,7),
                    47 => Item::get(Item::WOOL,7),
                    48 => Item::get(Item::WOOL,7),
                    49 => Item::get(Item::WOOL,7),
                    50 => Item::get(Item::WOOL,7),
                    51 => Item::get(Item::WOOL,7),
                    52 => Item::get(Item::WOOL,7),
                    53 => Item::get(Item::WOOL,5),
                    54 => Item::get(Item::WOOL,14)
                ]
            ]
		];
	}

	/**
	 * @param Position $pos
	 *
	 * @return Chest
	 */
	private function makeChestTile(Position $pos) : Chest{
		new Chest($pos->getLevel(), new CompoundTag("", [
			new StringTag("id", Tile::CHEST),
			new StringTag("CustomName", "Wardrobe"),
			new IntTag("x", $pos->getX() + 1),
			new IntTag("y", $pos->getY() - 2),
			new IntTag("z", $pos->getZ()),
			new IntTag("pairx", $pos->getX()),
			new IntTag("pairz", $pos->getZ())
		]));
		$tile = new Chest($pos->getLevel(), new CompoundTag("", [
			new StringTag("id", Tile::CHEST),
			new StringTag("CustomName", "Wardrobe"),
			new IntTag("x", $pos->getX()),
			new IntTag("y", $pos->getY() - 2),
			new IntTag("z", $pos->getZ()),
			new IntTag("pairx", $pos->getX() + 1),
			new IntTag("pairz", $pos->getZ())
		]));
		$tile->getInventory()->setContents($this->menuPages[0]);
		return $tile;
	}

	// API

	/*
	 * @param Player $owner
	 *
	 * @return DoubleChestInventory
	 */
	public function openWardrobe(Player $owner) {
		$chestTile = $this->makeChestTile($owner);
		if($chestTile->getInventory() instanceof DoubleChestInventory) {
			$owner->addWindow($chestTile->getInventory());
			return $chestTile->getInventory();
		}else{
			$this->getLogger()->debug("The inventory of a Chest Tile was not a DoubleChest! Tile info: ". $chestTile->__toString());
			return null;
		}
	}
	public function selectArmor(Player $player, string $slot, Armor $armor) {
	    $this->selections[$player->getName()] = [
	        $slot => $armor->getName()
        ];
    }
	public function applyArmor(Player $player) {
	    //
    }
	public function updateRainbowArmor() {
	    //
    }
}
