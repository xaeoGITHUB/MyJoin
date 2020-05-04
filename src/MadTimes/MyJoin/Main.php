<?php

namespace MadTimes\MyJoin;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\utils\TextFormat as Color;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\Config;

use MadTimes\MyJoin\commands\MyJoinCommand;

class Main extends PluginBase implements Listener
{
    public $prefix = Color::BOLD . Color::RED . "My" . Color::AQUA . "Join" . Color::DARK_GRAY . " > " . Color::RESET . Color::GRAY;
    
    public function onEnable()
    {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getServer()->getLogger()->info($this->prefix . Color::GREEN . 'Plugin was loaded.');
        $this->getServer()->getLogger()->info($this->prefix . Color::GREEN . 'Made by: github.com/MadTimesNET.');
        $this->getServer()->getCommandMap()->register('myjoin', new MyJoinCommand($this));
        $config = new Config($this->getDataFolder() . 'config.yml', Config::YAML);
        if (empty($config))
        {
            $config->set('messageformat', '§l§b{player}§r§7: §e{message}');
            $config->set('defaultmessage', 'The king is now online!');
            $config->set('cmdNoPerms', $this->prefix . Color::RED . 'You do not have permission to execute this command.');
            $config->set('cmdNoArgs', $this->prefix . Color::RED . 'Usage: /myjoin <message>');
            $config->set('cmdSuccess', $this->prefix . Color::GREEN . 'You successfully changed your joinstatus to: ' . Color::YELLOW . '{message}');
            $config->save();
        }
    }
    public function onJoin(PlayerJoinEvent $event)
    {
        $player = $event->getPlayer();
        $playername = $player->getName();
        $config = new Config($this->getDataFolder() . 'config.yml', Config::YAML);
        $playerfile = new Config($this->getDataFolder() . 'players/' . $player->getName() . '.yml', Config::YAML);
        if ($player->hasPermission("myjoin.myjoin"))
        {
            $message = $playerfile->get('status');
            $this->getServer()->broadcastMessage($this->convert($config->get('messageformat'), $playername, $message));
        }
    }
    public function onLogin(PlayerLoginEvent $event)
    {
        $player = $event->getPlayer();
        $config = new Config($this->getDataFolder() . 'config.yml', Config::YAML);
        $playerfile = new Config($this->getDataFolder() . 'players/' . $player->getName() . '.yml', Config::YAML);
        if (empty($playerfile))
        {
            $playerfile->set('status', $config->get('defaultmessage'));
            $playerfile->save();
        }
    }
    public function convert(string $string, $playername, $message) : string
    {
        $string = str_replace("{player}", $playername, $string);
        $string = str_replace("{message}", $message, $string);
        return $string;
    }
}
