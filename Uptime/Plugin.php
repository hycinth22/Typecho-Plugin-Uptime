<?php
/**
 * 博客运行时间显示插件
 * 
 * @package Uptime
 * @author aprikyblue
 * @version 1.0.0
 * @link https://github.com/aprikyblue/Typecho-Plugin-Uptime/
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
            'start_time', NULL, '2014-01-01 00:00:00',
            _t('开始时间'),
            _t('可以使用Javascript的Date构造参数接受的格式')
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
<script>
function startUptimeUpdate() {
    var start_time = '<?php echo $settings->start_time; ?>';
    var times = new Date().getTime() - new Date(start_time).getTime();
    times = Math.floor(times/1000); // milliseconds to seconds
    var days = Math.floor( times/(3600*24) );
    times %= 3600*24;
    var hours = Math.floor( times/3600 );
    times %= 3600;
    var minutes = Math.floor( times/60 );
    times %= 60;
    var seconds = Math.floor( times/1 );
    $(".uptime").html("本站已运行" + days + "天" + hours + "小时" + minutes + "分" + seconds + "秒");
    setTimeout(startUptimeUpdate, 1000);
}
startUptimeUpdate();
</script>
<!-- Uptime End -->
<?php
    }
}

?>