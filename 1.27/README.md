### uLogin - виджет авторизации через социальные сети ###

Donate link: http://ulogin.ru/

Tags: ulogin, login, social, authorization

Tested up to: 1.27.0

License: GPL3

Форма авторизации uLogin через социальные сети. Улучшенный аналог loginza.

uLogin — это инструмент, который позволяет пользователям получить единый доступ к различным Интернет-сервисам без необходимости повторной регистрации,
а владельцам сайтов — получить дополнительный приток клиентов из социальных сетей и популярных порталов (Google, Яндекс, Mail.ru, ВКонтакте, Facebook и др.)

### Установка ###
1. Скопировать содержимое в папку extensions
2. Добавить эту строчку в конце файла LocalSettings.php:

  wfLoadExtension( 'ULogin');

3. В файле LocalSettings.php можно задать используемые параметры:

  $wgULoginProviders = 'vkontakte,odnoklassniki,mailru'; //сервисы, выводимые сразу

  $wgULoginHidden = 'other'; //сервисы, выводимые при наведении

  $wgULoginDisplay = 'small'; //или 'panel'

  $wgULoginSort = 'relevant'; //или 'default' подробнее: https://ulogin.ru/help.php#sort
  
  $wgULoginTheme = 'classic'; //или 'flat'

### Frequently Asked Questions ###

**Нужно ли где-то регистрироваться, чтобы плагин заработал?**

Нет, плагин заработает сразу после установки!
