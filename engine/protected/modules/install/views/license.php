<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
                'id'=>'licensewidget',
                'cssFile'=>'jquery-ui-1.8.16.custom.css',
                'theme'=>'redmond',
                'themeUrl'=>Yii::app()->request->baseUrl.'/css/ui',
                'options'=>array(
                    'title'=>'Лицензионное соглашение',
                    'autoOpen'=>$this->autoOpen ? true : false,
                    'modal'=>'true',
                    'show'=>'puff',
                    'hide'=>'slide',
                    'width'=>'80%',
                    'height'=>'auto',
                    'resizable' =>false,
                    'buttons'=>array('Принять'=>'js:function() {
                        $("#InstallForm_agreeLicense").attr("checked", "checked");
                        $(this).dialog("close");
                    }'),
                ),
            ));
?>

<div>
    <h2>Лицензионное соглашение</h2>
    <p>
        GNU GPL <strong><?php echo tt('CMS Open Real Estate');?></strong> распространяется бесплатно под общедоступной открытой лицензией GNU GPL второй редакции.
        <a href="http://www.gnu.org/licenses/old-licenses/gpl-2.0.html" rel="nofollow">Оригинальный текст лицензии</a> и
        <a href="http://www.internet-law.ru/law/pc/gnu.htm" rel="nofollow">Перевод лицензии с английского на русский</a>
    </p>
    <p>
        Вы вольны распространять <strong><?php echo tt('CMS Open Real Estate');?></strong> в любом виде, сохраняя авторские права и копирайты в коде системы (php файлы).
    </p>
    <p>Убрать активную ссылку на наш сайт в футере пользовательской и администраторской частях сайта вы можете только после оплаты в размере 1000 рублей.</p>
	<p>Для этого <a href="http://monoray.ru/contact">напишите нам</a>, сообщив адрес домена для которого удаляются копирайты.</p>
</div>
    
<?php $this->endWidget('zii.widgets.jui.CJuiDialog');?>