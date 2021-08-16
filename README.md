## Учебный проект «Загрузчик страниц (Тестирование)» в рамках курса Hexlet (PHP-разработчик)
[![Actions Status](https://github.com/MT-cod/php-testing-project-lvl1/workflows/hexlet-check/badge.svg)](https://github.com/MT-cod/php-testing-project-lvl1/actions)
[![PHP%20CI](https://github.com/MT-cod/php-testing-project-lvl1/workflows/PHP%20CI/badge.svg)](https://github.com/MT-cod/php-testing-project-lvl1/actions)
[![Latest Stable Version](https://img.shields.io/packagist/v/mt-cod/php-testing-project-lvl1.svg)](https://packagist.org/packages/mt-cod/php-testing-project-lvl1)
<br>
[![Code Climate](https://codeclimate.com/github/MT-cod/php-testing-project-lvl1/badges/gpa.svg)](https://codeclimate.com/github/MT-cod/php-testing-project-lvl1)
[![Issue Count](https://codeclimate.com/github/MT-cod/php-testing-project-lvl1/badges/issue_count.svg)](https://codeclimate.com/github/MT-cod/php-testing-project-lvl1/issues)
[![Test Coverage](https://codeclimate.com/github/MT-cod/php-testing-project-lvl1/badges/coverage.svg)](https://codeclimate.com/github/MT-cod/php-testing-project-lvl1/coverage)

<h2>Цель</h2>
<p>Проект в значительной степени ориентирован на глубокую проработку происходящих в коде процессов: порядок выполнения, обработка ошибок и многое другое.</p>

<p>В этом проекте отрабатываются файловые операции, а также выполнение HTTP-запросов.</p>

<p>Важно правильно контролировать ход выполнения операций и реагировать на возникающие в процессе ошибки. В проекте используется логирование для отслеживания процесса выполнения кода и дебага.</p>

<p>Также в этом проекте идет активная работа с HTML. Для замены ссылок внутри страницы, нужно выполнить парсинг страницы в DOM. Из него с помощью языка запросов (селекторов) потребуется извлечь все ресурсы для скачивания и заменить их локальными ссылками.</p>

<p>И, конечно же, архитектура. Создание хороших абстракций и правильная организация процесса загрузки – ключевая задача при создании загрузчика страниц. В этом проекте много цепочек, много преобразований, много операций ввода/вывода.</p>
<h2>Описание</h2>
<p>PageLoader – утилита командной строки, которая скачивает страницы из интернета и сохраняет их на компьютере. Вместе со страницей она скачивает все ресурсы (картинки, стили и js) давая возможность открывать страницу без интернета.</p>


<p>Пример использования:</p>
<pre class="hljs"><code class="sh"><span style="color: #008080">$ </span>./page-loader https://ru.hexlet.io/courses <span style="color: #000080">-o</span> /var/tmp

Page was successfully downloaded into /var/tmp/ru-hexlet-io-courses.html
</code></pre>

<h3>Аскинемы с примерами:</h3>

<a href="https://asciinema.org/a/bTcfN43EkeFPNEm6zdZtNpKH1">Скачивание страницы - asciinema</a>
<br>
<a href="https://asciinema.org/a/8H1fPin1r4FKAHYP8FlWWuOpO">Скачивание изображений - asciinema</a>
<br>
<a href="https://asciinema.org/a/27DxJZ8EEHb2oyTlYrobOB5Yn">Скачивание остальных ресурсов - asciinema</a>
<br>
<a href="https://asciinema.org/a/epvQuUD8FpB8Ypx16hOHYLLc0">Логирование работы программы - asciinema</a>
<br>
<a href="https://asciinema.org/a/iqCu90Ejdj4XUehh20a6vWjmI">Обработка ошибок - asciinema</a>
