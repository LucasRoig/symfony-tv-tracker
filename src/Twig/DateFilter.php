<?php

namespace App\Twig;


use Carbon\Carbon;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class DateFilter extends AbstractExtension {
    public function getFilters(){
        return array(
            new TwigFilter('date_diff', array($this, 'dateDiff'))
        );
    }

    public function dateDiff($date){
        $date = Carbon::instance($date);
        $diff = Carbon::now()->diffInHours($date,false);
        if($diff < 0 && $diff > -24){
            return 'Today';
        }
        if($diff < 24){
            return 'Tomorrow';
        }
        Carbon::enableHumanDiffOption(Carbon::ONE_DAY_WORDS);
        return $date->diffForHumans();
    }
}