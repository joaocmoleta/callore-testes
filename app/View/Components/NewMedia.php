<?php

namespace App\View\Components;

use Illuminate\View\Component;

class NewMedia extends Component
{
    public $tg_name_c;
    public $cut_type;
    public $prefix;
    public $preImg;
    public $config;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($name, $cut, $idp, $preImg, $config)
    {
        $this->tg_name_c = $name;
        $this->cut_type = $cut ? $cut : 0;
        $this->prefix = $idp;
        $this->preImg = $preImg;
        $this->config = $config;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.new-media');
    }
}
