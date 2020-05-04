<?php

namespace Pytrox\MyJoin\commands;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat as Color;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

use Pytrox\MyJoin\Main;

class MyJoinCommand extends Command
{
    public $prefix = Color::BOLD . Color::RED . "Mad" . Color::AQUA . "Times" . Color::DARK_GRAY . " > " . Color::RESET . Color::GRAY;
    
    public function __construct(Main $plugin)
    {
        parent::__construct("myjoin", "Setze deine Join-Nachricht", '/joinstatus <Nachricht>', ['joinstatus', 'statusjoin']);
        $this->plugin = $plugin;
    }
    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        $permission_string = file_get_contents('/home/Cloud/players/permissions/' . $sender->getName() . '.json');
        $json_permission = json_decode($permission_string, true);
        $pf_string = file_get_contents('/home/Cloud/players/' . $sender->getName() . '.json');
        $json_pf = json_decode($pf_string, true);
        if ($json_permission['CBJoinStatus'] == true)
        {
            if (isset($args[0]))
            {
                $joinmsg = implode(' ', $args);
                $json_pf['CBMyJoin'] = $joinmsg;
                $newMyJoin = json_encode($json_pf);
                file_put_contents('/home/Cloud/players/' . $sender->getName() . '.json', $newMyJoin);
                $sender->sendMessage($this->prefix . 'Deine Beitritt-Nachricht ist nun: ' . Color::YELLOW . $joinmsg);
            }
            else
            {
                $sender->sendMessage($this->prefix . Color::RED . 'Du musst eine Nachricht angeben.');
            }
        }
        else
        {
            $sender->sendMessage($this->prefix . Color::RED . 'Du brauchst den ' . Color::GOLD . 'Premium-Rang' . Color::RED . ', um diesen Befehl zu nutzen.');
        }
    }
}
