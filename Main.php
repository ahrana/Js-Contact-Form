namespace Gkits/Main;

 use pocketmine\Server;
 use pocketmine\Player;
 use pocketmine\event\Listener;
 use pocketmine\event\entity\EntityInventoryChangeEvent;
 use pocketmine\event\player\PlayerItemHeldEvent;
 use pocketmine\entity\Entity;
 use pocketmine\entity\Effect;
 use pocketmine\item\enchantment\Enchantment;
 use pocketmine\plugin\PluginBase;
 use pocketmine\block\Block;
 use pocketmine\item\Item;
 use pocketmine\nbt\tag\{CompoundTag, IntTag, ListTag, StringTag, IntArrayTag};
 use pocketmine\nbt\NBT;
 use pocketmine\tile\Tile;
 use pocketmine\tile\Chest;
 use pocketmine\utils\Config;
 use pocketmine\command\Command;
 use pocketmine\command\CommandSender;
 use pocketmine\command\CommandExecutor;
 use pocketmine\command\ConsoleCommandSender;

class Main extends PluginBase implements Listener{

public function onEnable(){

    $this->saveDefaultConfig(); 
    $this->getServer()->getPluginManager()->registerEvents($this, $this);
    $this->getServer()->getLogger()->notice("
    * GKits enabled
    * Please dont use this plugin without permission!
    * Gkits Version 1.1"
    );
}

    public function onGKits(Player $player){
      $sharpness = Enchantment::getEnchantment(9);
      $sharpness->setLevel(0);
      $protection = Enchantment::getEnchantment(0);
      $protection->setLevel(0);
        $nbt = new CompoundTag('', [
			new StringTag('id', Tile::CHEST),
			new IntTag('GKit', 1),
			new IntTag('x', floor($player->x)),
			new IntTag('y', floor($player->y) - 4),
			new IntTag('z', floor($player->z))
		]);
		/** @var Chest $tile */
		$tile = Tile::createTile('Chest', $player->getLevel(), $nbt);
		$block = Block::get(Block::CHEST);
		$block->x = floor($tile->x);
		$block->y = floor($tile->y);
		$block->z = floor($tile->z);
		$block->level = $tile->getLevel();
		$block->level->sendBlocks([$player], [$block]);
       // Tank Kit
        $tankM = $this->getConfig()->get("Tank");
          $tank = Item::get(311, 0, 1);
          $tank->setCustomName("Â§4[GKit]\n Â§cTank Kit\n Â§6Cost: Â§a$$tankM");
          $tank->addEnchantment($protection);
          // Tank Kit ending

       // Soilder kit
        $soilderM = $this->getConfig()->get("Soilder");
        $soilder = Item::get(279, 0, 1);
        $soilder->setCustomName("Â§4[GKit]\n Â§cSoilder Kit\n Â§6Cost: Â§a$$soilderM");
        $soilder->addEnchantment($sharpness);
        $tile->getInventory()->setItem(0, $tank);
        $tile->getInventory()->setItem(1, $soilder);
		$player->addWindow($tile->getInventory());
  }
  
  public function gkitErrorSoilder(Player $player){
        $soilder = $this->getConfig()->get("Soilder");
      $player->sendMessage("Â§cÂ§l TheVortex Â§7>> Â§rÂ§fÂ§4You dont have enough money to buy this GKit! , you need atleast $$soilder");
  }

  public function gkitErrorTank(Player $player){
        $tank = $this->getConfig()->get("Tank");
      $player->sendMessage("Â§cÂ§l TheVortex Â§7>> Â§rÂ§fÂ§4You dont have enough money to buy this GKit! , you need atleast $$tank");
  }

  public function gkitTank(Player $player){
      $helmet = Item::get(310, 0, 1);
      $chest = Item::get(311, 0, 1);
      $leggings = Item::get(312, 0, 1);
      $boots = Item::get(313, 0, 1);
      $sword = Item::get(276, 0, 1);
      $test = "Â§rÂ§fÂ§2GKit Kit \nÂ§eTank I";

      // Adding Tank I
      $tank = [
          $test, 
          'Â§rÂ§fÂ§2GKit Kit',
          'Â§rÂ§fÂ§2GKit Kit',
          'Â§rÂ§fÂ§2GKit Kit'
      ];
      $tank1 = [
          'Â§rÂ§fÂ§2GKit Kit', 
          'Â§rÂ§fÂ§2GKit Kit',
          'Â§rÂ§fÂ§2GKit Kit',
          $test
      ];
      $random = $tank[mt_rand(0, (count($tank) - 1))];
      $random1 = $tank1[mt_rand(0, (count($tank1) - 1))];
      $chest->setCustomName($random);
      $leggings->setCustomName($random1);
      $helmet->setCustomName("Â§rÂ§fÂ§2GKit Kit");
      $boots->setCustomName("Â§rÂ§fÂ§2GKit Kit");
      $sword->setCustomName("Â§rÂ§fÂ§2GKit Kit \nÂ§ePoison I");

      // Adding enchantment , Protection IV
      $sharpness = Enchantment::getEnchantment(9);
      $protection = Enchantment::getEnchantment(0);
      $protection->setLevel(4);
      $sharpness->setLevel(4);
      $helmet->addEnchantment($protection);
      $chest->addEnchantment($protection);
      $leggings->addEnchantment($protection);
      $boots->addEnchantment($protection);
      $sword->addEnchantment($sharpness);

      // Adding GKit to inventory
      $player->getInventory()->addItem($helmet, $leggings, $chest, $boots, $sword);
      $player->sendMessage("Â§cÂ§l TheVortex Â§7>> Â§rÂ§fÂ§aYou just got the Tank Kit!");
  }

    public function gkitSoilder(Player $player){
      $helmet = Item::get(310, 0, 1);
      $chest = Item::get(311, 0, 1);
      $leggings = Item::get(312, 0, 1);
      $boots = Item::get(313, 0, 1);
      $sword = Item::get(276, 0, 1);
      $gills = "Â§rÂ§fÂ§2GKit Kit \nÂ§eGills I";
      $velocity = "Â§rÂ§fÂ§2GKit Kit \nÂ§eVelocity I";

      // Adding Velocity II or Gills I
      $gills = [
          $gills, 
          'Â§rÂ§fÂ§2GKit Kit',
          'Â§rÂ§fÂ§2GKit Kit',
          'Â§rÂ§fÂ§2GKit Kit'
      ];
      $velocity = [
          'Â§rÂ§fÂ§2GKit Kit', 
          'Â§rÂ§fÂ§2GKit Kit',
          'Â§rÂ§fÂ§2GKit Kit',
          $velocity
      ];
      $random = $gills[mt_rand(0, (count($gills) - 1))];
      $random1 = $velocity[mt_rand(0, (count($velocity) - 1))];
      $chest->setCustomName("Â§rÂ§fÂ§2GKit Kit");
      $leggings->setCustomName("Â§rÂ§fÂ§2GKit Kit");
      $helmet->setCustomName($random);
      $boots->setCustomName($random1);
      $sword->setCustomName("Â§rÂ§fÂ§2GKit Kit \nÂ§cCSN I");

      // Adding enchantment , Protection V
      $sharpness = Enchantment::getEnchantment(9);
      $sharpness->setLevel(5);
      $protection = Enchantment::getEnchantment(0);
      $protection->setLevel(5);
      $helmet->addEnchantment($protection);
      $chest->addEnchantment($protection);
      $leggings->addEnchantment($protection);
      $boots->addEnchantment($protection);
      $sword->addEnchantment($sharpness);

      // Adding GKit to inventory
      $player->getInventory()->addItem($helmet, $leggings, $chest, $boots, $sword);
      $player->sendMessage("Â§cÂ§l TheVortex Â§7>> Â§rÂ§fÂ§aYou just got the Soilder Kit!");
  }


  public function onGKit(EntityInventoryChangeEvent $event){
      $player = $event->getEntity();
      $gkit = $event->getNewItem();
      $api = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
      $tankM = $this->getConfig()->get("Tank");
      $soilderM = $this->getConfig()->get("Soilder");
      $money = $api->myMoney($player);

      if($gkit->getName() == "Â§4[GKit]\n Â§cTank Kit\n Â§6Cost: Â§a$$tankM"){
          if($money < $tankM){
              $this->gkitErrorTank($player);
              $event->setCancelled();
              $this->onGKits($player);
          } else {
              $this->gkitTank($player);
              $api->reduceMoney($player->getName(), $tankM);
              $event->setCancelled();
              $this->onGKits($player);
          }
      }
      if($gkit->getName() == "Â§4[GKit]\n Â§cSoilder Kit\n Â§6Cost: Â§a$$soilderM"){
          if($money < $soilderM){
              $this->gkitErrorSoilder($player);
              $event->setCancelled();
              $this->onGKits($player);
          } else {
              $this->gkitSoilder($player);
              $api->reduceMoney($player->getName(), $soilderM);
              $event->setCancelled();
              $this->onGKits($player);
          }
      }
  }

  public function onCommand(CommandSender $sender, Command $command, $label, array $args) {

      switch (strtolower($command->getName())) {

case "gkit":

        if(isset($args[0])) {
            switch($args[0]) {

                case "tank":

                $sender->sendMessage("Â§cÂ§l TheVortex Â§7>> Â§rÂ§fÂ§cThis isn't added yet! :(");

                return true;
                break;

                case "about":

                $sender->sendMessage("Â§lÂ§7=-= Â§cVortex Â§rFactions Â§7=-=Â§rÂ§f");
                $sender->sendMessage("Â§a - This plugin was developed by Teamblocket(Angel) Owner of @VortexZMcPe");
                $sender->sendMessage("Â§a - Please ask first @VortexZMcPe to use this plugin!");
                $sender->sendMessage("Â§a - Orginal Author: Teamblocket(Angel)");

                return true;
                break;

            }
        }
        else {
            $this->onGKits($sender);
            $sender->sendMessage(" Â§cÂ§l TheVortex Â§7>> Â§rÂ§fÂ§7Welcome to the GKit Menu!");

        }

        return true;
        break;

      }

  }

  public function onDisable(){
       $this->getLogger()->warning("Turning off GKits , bye bye!");
  }
}
