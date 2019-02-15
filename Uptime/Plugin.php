<?php
/**
 * 博客运行时间显示插件
 * 
 * @package Uptime
 * @author inkedawn
 * @version 1.1.0
 * @link https://github.com/inkedawn/Typecho-Plugin-Uptime/
 */
class Uptime_Plugin implements Typecho_Plugin_Interface
{
    /**
     * 激活插件方法,如果激活失败,直接抛出异常
     * 
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function activate()
    {
        Typecho_Plugin::factory('Widget_Archive')->footer = array('Uptime_Plugin', 'footer');
    }

    /**
     * 禁用插件方法,如果禁用失败,直接抛出异常
     * 
     * @static
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function deactivate(){}
    
    /**
     * 获取插件配置面板
     * 
     * @access public
     * @param Typecho_Widget_Helper_Form $form 配置面板
     * @return void
     */
    public static function config(Typecho_Widget_Helper_Form $form)
    {
        /** 开始时间 */
        $start_time = new Typecho_Widget_Helper_Form_Element_Text(
            'start_time', NULL, '2014/01/01 00:00:00',
            _t('开始时间（博客服务器时区）'),
            _t('可以使用php的strtotime()接受的格式，详细参见http://php.net/manual/zh/datetime.formats.time.php')
        );
        $form->addInput($start_time);
    }

    /**
     * 个人用户的配置面板
     * 
     * @access public
     * @param Typecho_Widget_Helper_Form $form
     * @return void
     */
    public static function personalConfig(Typecho_Widget_Helper_Form $form){}

    /**
     * 输出
     *
     * 语法: Uptime_Plugin::show();
     *
     * @access public
     * @return void
     */
    public static function show()
    {
        echo '<div style="display:inline-block;" class="uptime"></div>';
    }

     /**
     * 底部输出
     *
     * @access public
     * @return void
     */
    public static function footer()
    {
        Typecho_Widget::widget('Widget_Options')->to($options);
        $settings = $options->plugin('Uptime');
?>
<!-- Uptime Start -->
<script defer>
(function(){
	function timeDuration(time1, time2) {
		var duration = time2.getTime() - time1.getTime();
		// duration's unit is milliseconds

		duration /= 1000;
		// now, duration's unit is second
		var second = Math.floor(duration % 60);  // 60 seconds become 1 minute, the remainder is second

		duration /= 60;
		// now, duration's unit is minute
		var minute = Math.floor(duration % 60); // 60 minutes become 1 hour, the remainder is minute

		duration /= 60;
		// now, duration's unit is hour
		var hour = Math.floor(duration % 24); // 24 hour become 1 day, the remainder is hour

		duration /= 24;
		// now, duration's unit is day
		var day = Math.floor(duration);
		return {day:day, hour:hour, minute:minute, second:second}
	}
	setInterval(function(){
		// timestamp is seconds in php but milliseconds in js, difference of 1000 times.
		var start_timestamp = <?php echo strtotime($settings->start_time); ?>*1000; 
		var duration = timeDuration(new Date(start_timestamp),new Date());
		var text = "本站已运行" + duration.day + "天" + duration.hour + "小时" + duration.minute + "分" + duration.second + "秒";
		document.querySelector(".uptime").innerText = text;
	});
})();
</script>
<!-- Uptime End -->
<?php
    }
}

?>
