<?php

use app\assets\AppAsset;
$this->registerJsFile('@web/js/shorten.js', ['depends' => [AppAsset::class]]);
/** @var yii\web\View $this */

use yii\helpers\Html;

$this->title = 'Сервис коротких ссылок';
$this->registerCss(<<<CSS
#result {
    margin-top: 20px;
    font-size: 16px;
}
#qr-code {
    margin-top: 10px;
    cursor: pointer;
}
#error {
    color: red;
    margin-top: 10px;
}
CSS
);

$script = <<<JS
$('#shorten-btn').on('click', function(e) {
    e.preventDefault();
    $('#error').text('');
    $('#result').html('');

    var url = $('#url-input').val().trim();
    if (url === '') {
        $('#error').text('Введите URL');
        return;
    }

    $.ajax({
        url: '',
        type: 'POST',
        dataType: 'json',
        data: {url: url},
        success: function(data) {
            if(data.error){
                $('#error').text(data.error);
            } else if(data.success){
                var shortLink = '<a href="' + data.shortUrl + '" target="_blank">' + data.shortUrl + '</a>';
                var qrImg = '<img id="qr-code" src="' + data.qrCode + '" alt="QR код" title="Сканируйте QR код для перехода">';
                $('#result').html(shortLink + '<br>' + qrImg);
            }
        },
        error: function() {
            $('#error').text('Ошибка при обращении к серверу');
        }
    });
});
JS;

$this->registerJs($script);
?>

<h1><?= Html::encode($this->title) ?></h1>

<form id="shorten-form">
    <?= Html::input('text', 'url', '', ['id' => 'url-input', 'placeholder' => 'Введите URL', 'style' => 'width:400px; padding:6px; font-size:14px;']) ?>
    <?= Html::button('ОК', ['id' => 'shorten-btn', 'class' => 'btn btn-primary', 'style' => 'margin-left:10px;']) ?>
</form>

<div id="error"></div>
<div id="result"></div>
