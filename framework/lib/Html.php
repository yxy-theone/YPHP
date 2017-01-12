<?php
namespace framework\lib;

class Html
{
	/**
	 * 生成meta标签
	 */
	public static function meta($options)
    {
        $html = '<meta name="'.$options['name'].'" content="'.$options['content'].'">';
        return $html;
    }

    /**
	 * 生成css文件
	 */
	public static function cssFile($href)
    {
        $html = '<link rel="stylesheet" href="'.$href.'">';
        return $html;
    }

    /**
	 * 生成js文件
	 */
	public static function jsFile($src)
    {
    	$html = '<script src="'.$src.'"></script>';
        return $html;
    }
}