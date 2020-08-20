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
        Typecho_Plugin::factory('Widget_Archive')->header = array(__CLASS__, 'style_set');
        Typecho_Plugin::factory('Widget_Archive')->footer = array(__CLASS__, 'notice_set');
        return _t('MemorialDay 插件已启用');
    }

    public static function deactivate()
    {
        return _t('MemorialDay 插件已禁用');
    }

    public static function config(Typecho_Widget_Helper_Form $form)
    {
        $memorialDays = new Typecho_Widget_Helper_Form_Element_Text(
            'memorialDays',
            null,
            "0404,0512,0728,0918,1213",
            _t('纪念日期：'),
            _t('日期使用英文逗号<code>,</code>分隔，可以自行增加删除日期；如果使用了CDN，请自行刷新缓存。')
        );
        $form->addInput($memorialDays->addRule('required', _t('日期为必填项')));
        $memorialNotices = new Typecho_Widget_Helper_Form_Element_Textarea(
            'memorialNotices',
            null,
            "清明时节，在此缅怀追思先人前辈，深切悼念2020年新冠肺炎疫情牺牲烈士和逝世同胞,
深切缅怀5·12汶川特大地震中的遇难同胞！,
深切缅怀7·28唐山特大地震中的遇难同胞！,
居安思危，警钟长鸣！岁月无疆，英雄不朽！勿忘国耻，共襄复兴！,
沉痛哀悼南京大屠杀中遇难同胞，我们永远不会忘记这一黑暗历史！",
            _t('通知：'),
            _t('通知使用英文逗号<code>,</code>分隔，与日期一一对应，文字内容可以为空。')
        );
        $form->addInput($memorialNotices->addRule('required', _t('通知为必填项')));
        $memorialDayStyle = new Typecho_Widget_Helper_Form_Element_Textarea(
            'memorialDayStyle',
            null,
            "<style type='text/css'>html{ filter: grayscale(100%); -webkit-filter: grayscale(100%); -moz-filter: grayscale(100%); -ms-filter: grayscale(100%); -o-filter: grayscale(100%); filter: url('data:image/svg+xml;utf8,#grayscale'); filter:progid:DXImageTransform.Microsoft.BasicImage(grayscale=1); -webkit-filter: grayscale(1);}</style>",
            _t('纪念日样式：'),
            _t('默认以黑白滤镜覆盖全站，可以留空')
        );
        $form->addInput($memorialDayStyle);

        $customDays = new Typecho_Widget_Helper_Form_Element_Text(
            'customDays',
            null,
            "1001",
            _t('其他日期'),
            _t('日期使用英文逗号<code>,</code>分隔，可以自行增加删除日期；如果使用了CDN，请自行刷新缓存。')
        );
        $form->addInput($customDays->addRule('required', _t('日期为必填项')));
        $customDayNotices = new Typecho_Widget_Helper_Form_Element_Textarea(
            'customDayNotices',
            null,
            "国庆之际，愿祖国繁荣昌盛，国泰民安！",
            _t('通知：'),
            _t('通知使用英文逗号<code>,</code>分隔，与日期一一对应，文字内容可以为空。')
        );
        $form->addInput($customDayNotices->addRule('required', _t('通知为必填项')));
        $customDayStyle = new Typecho_Widget_Helper_Form_Element_Textarea(
            'customDayStyle',
            null,
            null,
            _t('自定义其他日期样式：'),
            _t('自定义其他日期样式，默认留空')
        );
        $form->addInput($customDayStyle);
    }

    public static function personalConfig(Typecho_Widget_Helper_Form $form)
    {
    }

    public static function style_set()
    {
        $memorialDays = Typecho_Widget::widget('Widget_Options')->plugin('MemorialDay')->memorialDays;
        $memorialDayStyle = Typecho_Widget::widget('Widget_Options')->plugin('MemorialDay')->memorialDayStyle;
        $memorialDay_arr = explode(",", $memorialDays);
        $customDays = Typecho_Widget::widget('Widget_Options')->plugin('MemorialDay')->customDays;
        $customDayStyle = Typecho_Widget::widget('Widget_Options')->plugin('MemorialDay')->customDayStyle;
        $customDay_arr = explode(",", $customDays);
        if (in_array(date('md'), $memorialDay_arr)) {
            echo $memorialDayStyle;
        } elseif (in_array(date('md'), $customDay_arr)) {
            echo $customDayStyle;
        }
    }

    public static function notice_set()
    {
        $memorialDays = Typecho_Widget::widget('Widget_Options')->plugin('MemorialDay')->memorialDays;
        $memorialDay_arr = explode(",", $memorialDays);
        $memorialNotices = Typecho_Widget::widget('Widget_Options')->plugin('MemorialDay')->memorialNotices;
        $memorialNotice_arr = explode(",", $memorialNotices);
        $customDays = Typecho_Widget::widget('Widget_Options')->plugin('MemorialDay')->customDays;
        $customDay_arr = explode(",", $customDays);
        $customDayNotices = Typecho_Widget::widget('Widget_Options')->plugin('MemorialDay')->customDayNotices;
        $customDayNotice_arr = explode(",", $customDayNotices);
        if (in_array(date('md'), $memorialDay_arr)) {
            $memorialNotice = $memorialNotice_arr[array_search(date('md'), $memorialDay_arr)];
            echo "<marquee scrollamount=7 onmouseover=this.stop() onmouseout=this.start()><span style='color:#b6b6b6;'>{$memorialNotice}</span></marquee>";
        } elseif (in_array(date('md'), $customDay_arr)) {
            $customDayNotice = $customDayNotice_arr[array_search(date('md'), $customDay_arr)];
            echo "<marquee scrollamount=7 onmouseover=this.stop() onmouseout=this.start()><span style='color:#b6b6b6;'>{$customDayNotice}</span></marquee>";
        }
    }
}