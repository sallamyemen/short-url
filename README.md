<h2>📘 Инструкция по развертыванию проекта "Сервис коротких ссылок"</h2>

<h3>🛠️ Требования</h3>
<ul>
  <li>PHP 7.4+</li>
  <li>Composer</li>
  <li>MySQL/MariaDB</li>
  <li>Веб-сервер (например, OpenServer, XAMPP, Nginx + PHP-FPM)</li>
  <li>Yii2 Basic Template</li>
</ul>

<hr>

<h3>1. 📥 Клонирование проекта</h3>

<pre><code>git clone https://github.com/your-username/short-url.git
cd short-url
</code></pre>

<p>Или скопируй проект в <code>C:\OSPanel\domains\short-url</code>, если используешь OpenServer.</p>

<hr>

<h3>2. 📦 Установка зависимостей</h3>

<pre><code>composer install
</code></pre>

<hr>

<h3>3. ⚙️ Настройка подключения к базе данных</h3>

<p>Создай базу данных, например <code>short_url</code>.</p>

<p>Пример в MySQL:</p>

<pre><code>CREATE DATABASE short_url CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
</code></pre>

<p>Настрой файл <code>config/db.php</code>:</p>

<pre><code>return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=short_url',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8mb4',
];
</code></pre>

<hr>

<h3>4. 🗃️ Применение миграций</h3>

<p>Выполни в терминале:</p>

<pre><code>php yii migrate
</code></pre>

<p>💡 Убедись, что <code>console</code> и <code>migrations</code> настроены корректно. В миграциях должны создаваться таблицы <code>link</code> и <code>link_log</code>.</p>

<hr>

<h3>5. 🖥️ Настройка веб-сервера</h3>

<h4>Для OpenServer:</h4>
<ul>
  <li>Создай домен <code>short-url</code></li>
  <li>Укажи путь к папке <code>web/</code> проекта:<br>
  <code>C:\OSPanel\domains\short-url\web</code></li>
  <li>Перезапусти OpenServer</li>
</ul>

<p>Теперь приложение будет доступно по адресу:</p>

<pre><code>http://short-url/
</code></pre>

<hr>

<h3>6. 🚀 Проверка работы</h3>

<ol>
  <li>Перейди по адресу сайта.</li>
  <li>Введи любую ссылку (например, <code>https://example.com</code>).</li>
  <li>Нажми "Сократить".</li>
  <li>Увидишь короткую ссылку и QR-код.</li>
  <li>Перейди по короткой ссылке — должна произойти переадресация на оригинальный URL.</li>
</ol>

<hr>

<h3>🔎 Полезные команды</h3>

<ul>
  <li><code>php yii migrate</code> — применить миграции.</li>
  <li><code>php yii cache/flush-all</code> — очистить кэш.</li>
  <li><code>php yii migrate/create create_link_table</code> — создать новую миграцию.</li>
</ul>

<h2>🧪 Тестовое задание: Сервис коротких ссылок с QR</h2>

<p><strong>📎 Задание:</strong> Выполните тестовое задание. Прикрепите ссылку на документ.</p>

<hr>

<h3>📌 Описание задачи</h3>

<ul>
  <li>Разработать сервис коротких ссылок с поддержкой генерации QR-кода.</li>
  <li>Стек технологий: <strong>Yii2</strong>, <strong>MySQL/MariaDB</strong>, <strong>jQuery</strong>, <strong>Bootstrap</strong>.</li>
  <li>Тип проекта: <strong>Yii2 Basic</strong>.</li>
</ul>

<hr>

<h3>🧩 Функциональные требования</h3>

<ol>
  <li>На главной странице расположена форма <code>&lt;input&gt;</code> для ввода URL.</li>
  <li>Рядом — кнопка <strong>ОК</strong>.</li>
  <li>При нажатии:
    <ul>
      <li>Проверяется валидность URL (формат, <code>http://</code> или <code>https://</code> и т.д.).</li>
      <li>Проверяется доступность ресурса.</li>
      <li>Если ресурс недоступен — отображается сообщение: <strong>"Данный URL не доступен"</strong>.</li>
      <li>Если проверка пройдена:
        <ul>
          <li>Сохраняем ссылку в БД.</li>
          <li>Генерируем короткую ссылку.</li>
          <li>Создаём QR-код.</li>
        </ul>
      </li>
      <li>Пользователь получает:
        <ul>
          <li>Сгенерированную короткую ссылку.</li>
          <li>QR-код.</li>
          <li>Без перезагрузки страницы (через Ajax).</li>
        </ul>
      </li>
    </ul>
  </li>
</ol>

<hr>

<h3>📱 QR-код</h3>

<ul>
  <li>QR-код открывается при наведении камеры телефона.</li>
  <li>Содержит короткую ссылку.</li>
  <li>Переход по QR-коду должен работать корректно.</li>
</ul>

<hr>

<h3>🔀 Редирект</h3>

<ul>
  <li>При переходе по короткой ссылке происходит перенаправление на оригинальный URL.</li>
</ul>

<hr>

<h3>📊 Логирование переходов</h3>

<ul>
  <li>При каждом переходе:
    <ul>
      <li>Фиксируется внешний IP пользователя.</li>
      <li>Обновляется счётчик переходов.</li>
    </ul>
  </li>
</ul>

<hr>

<h3>🗃️ База данных</h3>

<ul>
  <li>Структура на ваше усмотрение.</li>
  <li>Миграции — на ваше усмотрение. Если нет — предоставить <strong>SQL dump</strong> и <strong>версию СУБД</strong>.</li>
</ul>

<hr>

<h3>📦 Ограничения</h3>

<ul>
  <li><strong>Нельзя</strong> использовать сторонние API-сервисы.</li>
  <li>Все функции — локально через PHP, Yii2 и доступные библиотеки.</li>
</ul>

<hr>

<h3>📑 Инструкция по развертыванию</h3>

<p>Размещение — в отдельном разделе <code>README.md</code> </p>

<hr>

<h3>📁 Что сдавать?</h3>

<ul>
  <li>Исходный код проекта.</li>
  <li>Инструкция по развёртыванию.</li>
  <li>Ссылка на демонстрацию или архив/репозиторий (например, GitHub).</li>
</ul>

