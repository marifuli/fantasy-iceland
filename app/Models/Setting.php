<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;
    static $image_store_path = "settings/images";
    protected $guarded = [];

    static function home_slider_1() 
    {
        $check = self::query()->where('key', 'home_slider_1')->first();
        if($check) return '/storage/' . $check->value;
        return "/assetsv2/img/fb cover.jpg";
    }
    static function home_slider_2() 
    {
        $check = self::query()->where('key', 'home_slider_2')->first();
        if($check) return '/storage/' . $check->value;
        return "/assetsv2/img/cover2.jpg";
    }
    static function home_slider_3() 
    {
        $check = self::query()->where('key', 'home_slider_3')->first();
        if($check) return '/storage/' . $check->value;
        return "/assetsv2/img/cover3.jpg";
    }
    static function home_section_text_1() 
    {
        $check = self::query()->where('key', 'home_section_text_1')->first();
        if($check) return $check->value;
        return 'Welcome to <span>Fantasy Island</span>';
    }
    static function home_section_text_2() 
    {
        $check = self::query()->where('key', 'home_section_text_2')->first();
        if($check) return $check->value;
        return "It's unlimited fun for friends and family that you can enjoy all day long with us. We have started a new movie theater where block blaster movies are playing daily.";
    }
}
