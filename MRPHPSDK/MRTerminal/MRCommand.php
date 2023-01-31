<?php

namespace MRPHPSDK\MRTerminal;

require __DIR__."/../../MRPHPSDK/MRTerminal/MRTerminal.php";

class MRCommand{

    public $command;

    public $name;

    function __construct(){

    }

    public function handle($params){
        //-- Get params from terminal
        $this->command = isset($params[1])?$params[1]:"";
        $this->name = isset($params[2])?$params[2]:"";

        if($this->command == ''){
            $this->command = "help";
        }

        $terminal = new MRTerminal();
        $terminal->handle($this->command, $this->name);

    }

}