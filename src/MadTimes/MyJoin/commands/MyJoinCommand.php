<?php

namespace MadTimes\MyJoin\commands;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat as Color;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\Config;

use MadTimes\MyJoin\Main;

class MyJoinCommand extends Command
{
    public function __construct(Main $plugin)
    {
        parent::__construct("myjoin", "Set your own join-message", '/joinstatus <message>', ['joinstatus', 'statusjoin']);
        $this->plugin = $plugin;
    }
    public function convert(string $string, $newStatus) : string
    {
        $string = str_replace("{player}", $sender->getName(), $string);
        $string = str_replace("{message}", $message, $string);
        return $string;
    }
    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        $config = new Config($this->getDataFolder() . 'config.yml', Config::YAML);
        $playerfile = new Config($this->getDataFolder() . 'players/' . $sender->getName() . '.yml', Config::YAML);
        if ($sender->hasPermission('myjoin.myjoin'))
        {
            if (isset($args[0]))
            {
                $message = implode(' ', $args);
                $playerfile->set('status', $message);
                $playerfile->save();
                $sender->sendMessage($this->convert($config->get('cmdSuccess'), $sender->getName(), $message));
            }
            else
            {                
                $message = implode(' ', $args);
                $sender->sendMessage($this->convert($config->get('cmdNoArgs'), $sender->getName(), $message));
            }
        }
        else
        {
            $sender->sendMessage($this->convert($config->get('cmdNoPerms'), $sender->getName(), $message));
        }
    }
}
