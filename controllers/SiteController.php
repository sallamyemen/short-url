<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\web\NotFoundHttpException;
use app\models\Link;
use app\models\Log;

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class SiteController extends Controller
{
    public function actionIndex()
    {
        $this->layout = 'main';

        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $url = Yii::$app->request->post('url');

            // Валидация URL
            if (!$this->validateUrl($url)) {
                return ['error' => 'Неверный URL. Убедитесь, что ссылка корректна и начинается с http:// или https://'];
            }

            // Проверка доступности URL
            if (!$this->checkUrlAvailability($url)) {
                return ['error' => 'Данный URL не доступен'];
            }

            // Поиск или создание новой ссылки
            $link = Link::findOne(['original_url' => $url]);
            if (!$link) {
                $link = new Link();
                $link->original_url = $url;
                $link->short_code = $this->generateUniqueShortCode();

                if (!$link->save()) {
                    return ['error' => 'Ошибка при сохранении ссылки: ' . json_encode($link->getErrors())];
                }
            }

            // Генерация короткой ссылки и QR
            $shortUrl = Yii::$app->request->hostInfo . '/r/' . $link->short_code;
            $qrCodeBase64 = $this->generateQrCode($shortUrl); // строка, не массив

            return [
                'success' => true,
                'shortUrl' => $shortUrl,
                'qrCode' => $qrCodeBase64,
            ];
        }

        // Обычный GET-запрос
        return $this->render('index');
    }


    // Редирект по короткой ссылке
    public function actionRedirect($code)
    {
        $link = Link::findOne(['short_code' => $code]);
        if (!$link) {
            throw new NotFoundHttpException('Ссылка не найдена.');
        }

        //print_r(Yii::$app->request->userIP); die;
        // Логируем переход
        $log = new Log();
        $log->link_id = $link->id;
        $log->ip_address = Yii::$app->request->userIP;
        $log->save();

        // Увеличиваем счетчик переходов
        $link->updateCounters(['click_count' => 1]);

        return $this->redirect($link->original_url);
    }


    // Валидация URL через filter_var с дополнительной проверкой схемы
    protected function validateUrl($url)
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }
        $parsed = parse_url($url);
        if (!in_array($parsed['scheme'], ['http', 'https'])) {
            return false;
        }
        return true;
    }

    // Проверка доступности URL через curl
    protected function checkUrlAvailability($url)
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, true);         // Только заголовки, без тела
        curl_setopt($curl, CURLOPT_NOBODY, false);        // Включаем тело (на всякий случай)
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0'); // Мимикрия под браузер

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        return ($httpCode >= 200 && $httpCode < 400);
    }

    // Генерация уникального короткого кода 6 символов (буквы+цифры)
    protected function generateUniqueShortCode($length = 6)
    {
        $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        do {
            $code = '';
            for ($i = 0; $i < $length; $i++) {
                $code .= $chars[random_int(0, strlen($chars) - 1)];
            }
            $exists = Link::find()->where(['short_code' => $code])->exists();
        } while ($exists);
        return $code;
    }

    // Генерация QR-кода в base64 (используем библиотеку "endroid/qr-code")
    protected function generateQrCode($shortUrl)
    {
        $qrCode = QrCode::create($shortUrl)
            ->setSize(200);

        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        return $result->getDataUri();
    }
}
