<?php

namespace App;

use Sse\SSE;

class PlantChangedWorker extends Thread
{
    private $sse;

    public function __construct(SSE $sse){
        $this->sse = $sse;
    }

    public function run(){
        $sse->start();
    }
}
   
