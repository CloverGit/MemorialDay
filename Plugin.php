<?php
/**
 * 「特殊节日使用」在国家公祭日、全国哀悼日时网站增加灰色滤镜。<a href="https://github.com/sy-records/MemorialDay" target="_blank">Github</a>
 *
 * @package MemorialDay
 * @author 沈唁
 * @version 1.0.0
 * @link https://qq52o.me
 */

class MemorialDay_Plugin implements Typecho_Plugin_Interface
{
    public static function activate()
    {
        Typecho_Plugin::factory('Widget_Archive')->header = array(__CLASS__, 'website_set');
        Typecho_Plugin::factory('Widget_Archive')->footer = array(__CLASS__, 'notice_set');
        return _t('MemorialDay 插件已启用');
    }

    public static function deactivate()
    {
        return _t('MemorialDay 插件已禁用');
    }

    public static function config(Typecho_Widget_Helper_Form $form)
    {
        $days = new Typecho_Widget_Helper_Form_Element_Text(
            'days',
            null,
            "0404,0512,0728,0918,1213",
            _t('日期：'),
            _t('日期使用英文逗号<code>,</code>分隔，可以自行增加删除日期；如果使用了CDN，请自行刷新缓存。')
        );
        $form->addInput($days->addRule('required', _t('日期为必填项')));
       $notices = new Typecho_Widget_Helper_Form_Element_Text(
            'notices',
            null,
            "清明时节，在此缅怀追思先人前辈，深切悼念2020年新冠肺炎疫情牺牲烈士和逝世同胞,
            深切缅怀5·12汶川特大地震中的遇难同胞！,
            深切缅怀7·28唐山特大地震中的遇难同胞！,
            居安思危，警钟长鸣！岁月无疆，英雄不朽！勿忘国耻，共襄复兴！,
            沉痛哀悼南京大屠杀中遇难同胞，我们永远不会忘记这一黑暗历史！",
            _t('通知：'),
            _t('通知使用英文逗号<code>,</code>分隔，数量必须与日期数相符，内容可以设置为空。')
        );
        $form->addInput($notices->addRule('required', _t('通知为必填项')));
    }

    public static function personalConfig(Typecho_Widget_Helper_Form $form)
    {
    }

    public static function website_set()
    {
        $days = Typecho_Widget::widget('Widget_Options')->plugin('MemorialDay')->days;
        $day_arr = explode(",", $days);
        if (in_array(date('md'), $day_arr)) {
            echo "<style type='text/css'>html{ filter: grayscale(100%); -webkit-filter: grayscale(100%); -moz-filter: grayscale(100%); -ms-filter: grayscale(100%); -o-filter: grayscale(100%); filter: url('data:image/svg+xml;utf8,#grayscale'); filter:progid:DXImageTransform.Microsoft.BasicImage(grayscale=1); -webkit-filter: grayscale(1);}</style>";
        }
    }

    public static function notice_set()
    {
        $days = Typecho_Widget::widget('Widget_Options')->plugin('MemorialDay')->days;
        $notices = Typecho_Widget::widget('Widget_Options')->plugin('MemorialDay')->notices;
        $day_arr = explode(",", $days);
        $notice_arr = explode(",", $notices);
        if (in_array(date('md'), $day_arr)) {
            $notice = $notice_arr[array_search(date('md'), $day_arr)];
            echo "<marquee scrollamount=7 onmouseover=this.stop() onmouseout=this.start() bgcolor='#fffef9'><span style='color:#007947;'>{$notice}</span></marquee>";
        }
    }
}