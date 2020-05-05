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
    public function convert(string $string, $sendername, $message) : string
    {
        $string = str_replace("{player}", $sendername, $string);
        $string = str_replace("{message}", $message, $string);
        return $string;
    }
    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        $sendername = $sender->getName();
        $config = new Config($this->plugin->getDataFolder() . 'config.yml', Config::YAML);
        $playerfile = new Config($this->plugin->getDataFolder() . 'players/' . $sender->getName() . '.yml', Config::YAML);
        if ($sender->hasPermission('myjoin.myjoin'))
        {
            if (isset($args[0]))
            {
                $message = implode(' ', $args);
                $playerfile->set('status', $message);
                $playerfile->save();
                $sender->sendMessage($this->convert($config->get('cmdSuccess'), $sendername, $message));
            }
            else
            {                
                $message = implode(' ', $args);
                $sender->sendMessage($this->convert($config->get('cmdNoArgs'), $sendername, $message));
            }
        }
        else
        {
            $sender->sendMessage($this->convert($config->get('cmdNoPerms'), $sendername, $message));
        }
    }
}
