<?php
namespace framework\lib;

class View
{
	/**
	 * 标题
	 * @var string
	 */
	static $title;//标题

	/**
	 * 文档元信息
	 * @var array
	 * @see registerMetaTag()
	 */
	static $metaTags;

	/**
	 * css代码块
     * @var array
     * @see registerCss()
     */
    static $css;

    /**
     * css文件
     * @var array
     * @see registerCssFile()
     */
    static $cssFiles;

    /**
     * js代码块
     * @var array
     * @see registerJs()
     */
    static $js;

    /**
     * js文件
     * @var array
     * @see registerJsFile()
     */
    static $jsFiles;

    /**
     * 注册meta元信息，保存在metaTags数组中
     */
    static function registerMetaTag($options)
    {
        self::$metaTags[] = Html::meta($options);
    }

    /**
     * 获取元信息meta标签
     */
    static function getMetaTag()
    {
    	if(!empty(self::$metaTags)){
    		$metas = implode("\n", self::$metaTags);
    		echo $metas;
    	}
    }

    /**
     * 注册css代码块，保存在css数组中
     */
    static function registerCSS($code)
    {
        self::$css[] = $code;
    }

    /**
     * 获取css代码块
     */
    static function getCss()
    {
    	if(!empty(self::$css)){
    		$css = implode("\n", self::$css);
    		echo "<style>\n".$css."\n</style>";
    	}
    }

    /**
     * 注册css文件，保存在cssFiles数组中
     */
    static function registerCssFile($href)
    {
        self::$cssFiles[] = Html::cssFile($href);
    }

    /**
     * 获取css文件
     */
    static function getCssFile()
    {
    	if(!empty(self::$cssFiles)){
    		$css_file = implode("\n", self::$cssFiles);
    		echo $css_file;
    	}
    }

    /**
     * 注册js文件，保存在jsFiles数组中
     */
    static function registerJsFile($src)
    {
        self::$jsFiles[] = Html::jsFile($src);
    }

    /**
     * 获取js文件
     */
    static function getJsFile()
    {
    	if(!empty(self::$jsFiles)){
    		$js_file = implode("\n", self::$jsFiles);
    		echo $js_file;
    	}
    }

    /**
     * 注册js代码块，保存在js数组中
     */
    static function registerJS($code)
    {
        self::$js[] = $code;
    }

    /**
     * 获取js代码块
     */
    static function getJs()
    {
    	if(!empty(self::$js)){
    		$js = implode("\n", self::$js);
    		echo "<script>\n".$js."\n</script>";
    	}
    }
}