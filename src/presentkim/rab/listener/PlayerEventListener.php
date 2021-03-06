<?php

namespace presentkim\rab\listener;

use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\Item;
use pocketmine\level\Position;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\tile\Sign;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as TE;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerQuitEvent;
use presentkim\rab\RunnersAndBeast as Plugin;

class PlayerEventListener implements Listener{

    /** @var Plugin */
    private $owner = null;

    public function __construct(){
        $this->owner = Plugin::getInstance();
    }

    public function onPlayerDeathEvent(PlayerDeathEvent $event) : void{
        $jugador = $event->getEntity();
        $mapa = $jugador->getLevel()->getFolderName();
        if (in_array($mapa, $this->owner->arenas)) {
            $event->setDeathMessage("");
            if ($event->getEntity()->getLastDamageCause() instanceof EntityDamageByEntityEvent) {
                $asassin = $event->getEntity()->getLastDamageCause()->getDamager();
                if ($asassin instanceof Player) {
                    foreach ($jugador->getLevel()->getPlayers() as $pl) {
                        $pl->sendMessage($jugador->getNameTag() . TE::DARK_RED . " was killed by " . $asassin->getNameTag());
                    }
                }
            } else {
                foreach ($jugador->getLevel()->getPlayers() as $pl) {
                    $pl->sendMessage(TE::RED . $jugador->getNameTag() . TE::DARK_RED . " died");
                }
            }
            $jugador->setNameTag($jugador->getName());
        }
    }

    public function onPlayerQuitEvent(PlayerQuitEvent $event) : void{
        $pl = $event->getPlayer();
        $level = $pl->getLevel()->getFolderName();
        if (in_array($level, $this->owner->arenas)) {
            $pl->removeAllEffects();
            $pl->getInventory()->clearAll();
            $pl->setNameTag($pl->getName());
            $this->owner->chang($pl);
        }
    }

    public function onPlayerInteractEvent(PlayerInteractEvent $event) : void{
        $player = $event->getPlayer();
        $block = $event->getBlock();
        $tile = $player->getLevel()->getTile($block);

        if ($tile instanceof Sign) {
            if (($this->owner->mode == 26) && (in_array($player->getName(), $this->owner->op))) {
                $tile->setText(TE::AQUA . "[Join]", TE::GREEN . "0 / 20", "§f" . $this->owner->currentLevel, Plugin::$prefix);
                $this->owner->refreshArenas();
                $this->owner->currentLevel = "";
                $this->owner->mode = 0;
                $player->sendMessage(Plugin::$prefix . "Arena Registered!");
                array_shift($this->owner->op);
            } else {
                $text = $tile->getText();
                if ($text[3] == Plugin::$prefix) {
                    if ($text[0] == TE::AQUA . "[Join]") {
                        $config = new Config($this->owner->getDataFolder() . "/config.yml", Config::YAML);
                        $slots = new Config($this->owner->getDataFolder() . "/slots.yml", Config::YAML);
                        $namemap = str_replace("§f", "", $text[2]);
                        $level = Server::getInstance()->getLevelByName($namemap);
                        if (strpos($player->getNameTag(), "§c(Beast)") !== false) {
                            $team = TE::RED . "Beast";
                            if ($slots->get("slot6" . $namemap) == null) {
                                $thespawn = $config->get($namemap . "Spawn2");
                                $slots->set("slot6" . $namemap, $player->getName());
                            } else {
                                $player->sendMessage(Plugin::$prefix . TE::RED . "There is already Beast in this game.");
                                goto noequip;
                            }
                        } elseif ($slots->get("slot1" . $namemap) == null) {
                            $thespawn = $config->get($namemap . "Spawn1");
                            $slots->set("slot1" . $namemap, $player->getName());
                            $player->setNameTag("§b(Runner)" . TE::GREEN . $player->getName());
                            $team = TE::AQUA . "Runner";
                        } elseif ($slots->get("slot2" . $namemap) == null) {
                            $thespawn = $config->get($namemap . "Spawn1");
                            $slots->set("slot2" . $namemap, $player->getName());
                            $player->setNameTag("§b(Runner)" . TE::GREEN . $player->getName());
                            $team = TE::AQUA . "Runner";
                        } elseif ($slots->get("slot3" . $namemap) == null) {
                            $thespawn = $config->get($namemap . "Spawn1");
                            $slots->set("slot3" . $namemap, $player->getName());
                            $player->setNameTag("§b(Runner)" . TE::GREEN . $player->getName());
                            $team = TE::AQUA . "Runner";
                        } elseif ($slots->get("slot4" . $namemap) == null) {
                            $thespawn = $config->get($namemap . "Spawn1");
                            $slots->set("slot4" . $namemap, $player->getName());
                            $player->setNameTag("§b(Runner)" . TE::GREEN . $player->getName());
                            $team = TE::AQUA . "Runner";
                        } elseif ($slots->get("slot5" . $namemap) == null) {
                            $thespawn = $config->get($namemap . "Spawn1");
                            $slots->set("slot5" . $namemap, $player->getName());
                            $player->setNameTag("§b(Runner)" . TE::GREEN . $player->getName());
                            $team = TE::AQUA . "Runner";
                        } elseif ($slots->get("slot6" . $namemap) == null) {
                            $thespawn = $config->get($namemap . "Spawn2");
                            $slots->set("slot6" . $namemap, $player->getName());
                            $player->setNameTag("§c(Beast)" . TE::GOLD . $player->getName());
                            $team = TE::RED . "Beast";
                        } elseif ($slots->get("slot7" . $namemap) == null) {
                            $thespawn = $config->get($namemap . "Spawn1");
                            $slots->set("slot7" . $namemap, $player->getName());
                            $player->setNameTag("§b(Runner)" . TE::GREEN . $player->getName());
                            $team = TE::AQUA . "Runner";
                        } elseif ($slots->get("slot8" . $namemap) == null) {
                            $thespawn = $config->get($namemap . "Spawn1");
                            $slots->set("slot8" . $namemap, $player->getName());
                            $player->setNameTag("§b(Runner)" . TE::GREEN . $player->getName());
                            $team = TE::AQUA . "Runner";
                        } elseif ($slots->get("slot9" . $namemap) == null) {
                            $thespawn = $config->get($namemap . "Spawn1");
                            $slots->set("slot9" . $namemap, $player->getName());
                            $player->setNameTag("§b(Runner)" . TE::GREEN . $player->getName());
                            $team = TE::AQUA . "Runner";
                        } elseif ($slots->get("slot10" . $namemap) == null) {
                            $thespawn = $config->get($namemap . "Spawn1");
                            $slots->set("slot10" . $namemap, $player->getName());
                            $player->setNameTag("§b(Runner)" . TE::GREEN . $player->getName());
                            $team = TE::AQUA . "Runner";
                        } elseif ($slots->get("slot11" . $namemap) == null) {
                            $thespawn = $config->get($namemap . "Spawn2");
                            $slots->set("slot11" . $namemap, $player->getName());
                            $player->setNameTag("§c(Beast)" . TE::GREEN . $player->getName());
                            $team = TE::RED . "Beast";
                        } elseif ($slots->get("slot12" . $namemap) == null) {
                            $thespawn = $config->get($namemap . "Spawn1");
                            $slots->set("slot12" . $namemap, $player->getName());
                            $player->setNameTag("§b(Runner)" . TE::GREEN . $player->getName());
                            $team = TE::AQUA . "Runner";
                        } elseif ($slots->get("slot13" . $namemap) == null) {
                            $thespawn = $config->get($namemap . "Spawn1");
                            $slots->set("slot13" . $namemap, $player->getName());
                            $player->setNameTag("§b(Runner)" . TE::GREEN . $player->getName());
                            $team = TE::AQUA . "Runner";
                        } elseif ($slots->get("slot14" . $namemap) == null) {
                            $thespawn = $config->get($namemap . "Spawn1");
                            $slots->set("slot14" . $namemap, $player->getName());
                            $player->setNameTag("§b(Runner)" . TE::GREEN . $player->getName());
                            $team = TE::AQUA . "Runner";
                        } elseif ($slots->get("slot15" . $namemap) == null) {
                            $thespawn = $config->get($namemap . "Spawn1");
                            $slots->set("slot15" . $namemap, $player->getName());
                            $player->setNameTag("§b(Runner)" . TE::GREEN . $player->getName());
                            $team = TE::AQUA . "Runner";
                        } elseif ($slots->get("slot16" . $namemap) == null) {
                            $thespawn = $config->get($namemap . "Spawn2");
                            $slots->set("slot16" . $namemap, $player->getName());
                            $player->setNameTag("§c(Beast)" . TE::GOLD . $player->getName());
                            $team = TE::RED . "Beast";
                        } elseif ($slots->get("slot17" . $namemap) == null) {
                            $thespawn = $config->get($namemap . "Spawn1");
                            $slots->set("slot17" . $namemap, $player->getName());
                            $player->setNameTag("§b(Runner)" . TE::GREEN . $player->getName());
                            $team = TE::AQUA . "Runner";
                        } elseif ($slots->get("slot18" . $namemap) == null) {
                            $thespawn = $config->get($namemap . "Spawn1");
                            $slots->set("slot18" . $namemap, $player->getName());
                            $player->setNameTag("§b(Runner)" . TE::GREEN . $player->getName());
                            $team = TE::AQUA . "Runner";
                        } elseif ($slots->get("slot19" . $namemap) == null) {
                            $thespawn = $config->get($namemap . "Spawn1");
                            $slots->set("slot19" . $namemap, $player->getName());
                            $player->setNameTag("§b(Runner)" . TE::GREEN . $player->getName());
                            $team = TE::AQUA . "Runner";
                        } elseif ($slots->get("slot20" . $namemap) == null) {
                            $thespawn = $config->get($namemap . "Spawn2");
                            $slots->set("slot20" . $namemap, $player->getName());
                            $player->setNameTag("§c(Beast)" . TE::GREEN . $player->getName());
                            $team = TE::RED . "Beast";
                        } else {
                            $player->sendMessage(Plugin::$prefix . TE::RED . "No places available.");
                            goto noequip;
                        }
                        $slots->save();
                        $player->getInventory()->clearAll();
                        $player->removeAllEffects();
                        $player->setMaxHealth(20);
                        $player->setHealth(20);
                        $player->setFood(20);
                        $spawn = new Position($thespawn[0] + 0.5, $thespawn[1], $thespawn[2] + 0.5, $level);
                        $level->loadChunk($spawn->getFloorX(), $spawn->getFloorZ());
                        $player->teleport($spawn, 0, 0);
                        if (strpos($player->getNameTag(), "§c(Beast)") !== false) {
                            $player->setGamemode(0);
                            $player->getInventory()->setHelmet(Item::get(Item::DIAMOND_HELMET));
                            $player->getInventory()->setChestplate(Item::get(Item::DIAMOND_CHESTPLATE));
                            $player->getInventory()->setLeggings(Item::get(Item::DIAMOND_LEGGINGS));
                            $player->getInventory()->setBoots(Item::get(Item::DIAMOND_BOOTS));
                            $player->getInventory()->setItem(0, Item::get(Item::DIAMOND_SWORD, 0, 1));
                            $player->getInventory()->setItem(1, Item::get(Item::GOLDEN_APPLE, 0, 3));
                            $player->getInventory()->setItem(2, Item::get(Item::BREAD, 0, 5));
                            $player->getInventory()->setItem(3, Item::get(Item::BOW, 0, 1));
                            $player->getInventory()->setItem(4, Item::get(Item::ARROW, 0, 15));
                            $player->getInventory()->sendArmorContents($player);
                            $player->getInventory()->setHotbarSlotIndex(0, 0);
                        }
                        $player->sendMessage(Plugin::$prefix . "-[runner|beast]-    you are a " . $team);
                        foreach ($level->getPlayers() as $playersinarena) {
                            $playersinarena->sendMessage($player->getNameTag() . " §f joined");
                        }
                        noequip:
                    } else {
                        $player->sendMessage(Plugin::$prefix . "you cant join now");
                    }
                }
            }
        } elseif (in_array($player->getName(), $this->owner->op) && $this->owner->mode == 1) {
            $config = new Config($this->owner->getDataFolder() . "/config.yml", Config::YAML);
            $config->set($this->owner->currentLevel . "Spawn" . $this->owner->mode, [
              $block->getX(),
              $block->getY() + 1,
              $block->getZ(),
            ]);
            $player->sendMessage(Plugin::$prefix . "Spawn Runneres registered!");
            $this->owner->mode++;
            $config->save();
        } elseif (in_array($player->getName(), $this->owner->op) && $this->owner->mode == 2) {
            $config = new Config($this->owner->getDataFolder() . "/config.yml", Config::YAML);
            $config->set($this->owner->currentLevel . "Spawn" . $this->owner->mode, [
              $block->getX(),
              $block->getY() + 1,
              $block->getZ(),
            ]);
            $player->sendMessage(Plugin::$prefix . "Spawn Beast registered!");
            $config->set("arenas", $this->owner->arenas);
            $config->set($this->owner->currentLevel . "start", 0);
            $player->sendMessage(Plugin::$prefix . "Touch a sign to register Arena!");
            $spawn = Server::getInstance()->getDefaultLevel()->getSafeSpawn();
            Server::getInstance()->getDefaultLevel()->loadChunk($spawn->getFloorX(), $spawn->getFloorZ());
            $player->teleport($spawn, 0, 0);
            $config->save();
            $this->owner->mode = 26;
        }
    }
}