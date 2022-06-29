<?php
/*
* HTML Management
*/
namespace App\Lib;

class Html
{
    public static function link($parram, $title)
    {
        if ($parram) {
            return "<a {$parram}>{$title}</a>\n";
        }
        return null;
    }

    public static function surround($tag, $parram, $html)
    {
        if ($tag && $html) {
            return "<{$tag}{$parram}>{$html}</{$tag}>\n";
        } elseif ($html) {
            return $html;
        }
        return null;
    }


    public static function input($params, $b_end = null)
    {
        return "<input $params>$b_end";
    }

    public static function submit($value = "Send")
    {
        return "<button type=\"submit\">$value</button>\n";
    }
}
