<?php

class Background
{
    public $bg;
    public $number;


    public function get_bg($number)
    {
        $this->number = $number;

        if ($this->number % 2 == 0) {
            $this->bg = '#F9F7F7';
        } else {
            $this->bg = '#fff';
        }
        return $this->bg;
    }

    public function __toString()
    {
        $this->get_bg();
        return $this->bg;
    }

}
