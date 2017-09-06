<?php
# Easy Newsletter 0.3
# Created by: Flux
# Language: Russian
# ------------------------------------------------------------------------
$lang['title'] = "Модуль управления рассылкой для MODx Evolution";
$lang['links_subscribers'] = "Подписчики";
$lang['links_newsletter'] = "Письма";
$lang['links_configuration'] = "Настройки";
$lang['links_categories'] = "Категории";
//
$lang['links_help'] = "Помощь";
//
$lang['links_import'] = "Импорт подписчиков из csv";
$lang['import_exclude'] = "Вы можете исключить строки из импорта.<br> Указываются в формате 1,2,3";
$lang['import_notify'] = "Контакты, которые будут импортированы в модуль";

$lang['import_title'] = "Загрузить из csv";
$lang['import_sub'] = "Загрузить файл";
$lang['import_upltxt'] = "Загрузка файла";
$lang['import_not_choose'] = "Вы не выбрали файл!";
$lang['import_submit'] = "Импортировать";
#--
$lang['import_process'] = "Загружено ";
$lang['import_field_numbers'] = "Укажите номера колонок, согласно их расположению в файле";
$lang['import_files_info'] = "Выберите файл формата .csv";
$lang['import_files_wait'] = "Пожалуйста, подождите идет проверка файла и его обработка.";
$lang['import_files_wrongtype'] = "К загрузке разрешены только файлы с расширением .csv";
$lang['import_files_again'] = "Повторить";
$lang['import_info'] = 'Загружаемый файл  должен быть формата :<br>email-1;firstname-1;lastname-1<br>email-2;firstname-2;lastname-2<br>В противном случае вы получите неверные данные в БД!';
#--
// ---
$lang['choosefile'] = "Выберите файл для импорта";
$lang['cancel'] = "Отмена";
$lang['submit'] = "Отправить";
$lang['savachanges'] = "Сохранить изменения";
// ---

$lang['backup_btn'] = "Резервное копирование";

$lang['mailinglist'] = "Пожалуйста введите ваше имя и e-mail адрес чтобы подписаться или отписаться от новостной рассылки.";
$lang['firstname'] = "Имя";
$lang['lastname'] = "Фамилия";
$lang['email'] = "E-mail адрес";
$lang['submit'] = "Отправить";
$lang['subscribe'] = "Подписаться";
$lang['unsubscribe'] = "Отписаться";
$lang['alreadysubscribed'] = "Такой почтовый адрес уже зарегистрирован.";
$lang['notsubscribed'] = "Этот почтовый адрес отсутсвует в нашей системе.";
$lang['subscribesuccess'] = "Спасибо Вам за то, что подписались на наши новости";
$lang['unsubscribesuccess'] = "Вы только что отписались от нашей новостной ленты.";
$lang['notvalidemail'] = "Почтовый адрес неправильного формата. Пожалуйста, попробуйте еще раз.";

$lang['newsletter_noposts'] = "Вы не создали еще ни одного письма.";
$lang['newsletter_test'] = "Тестовая отправка (e-mail адрес используется в <strong>настройках</strong>).";
$lang['newsletter_testmail'] = "Тест";
$lang['newsletter_delete_alert'] = "Вы действительно хотите удалить:";
$lang['newsletter_send_alert1'] = "Вы хотите отправить сообщение:";
$lang['newsletter_send_alert2'] = "ВНИМАНИЕ: Письмо отправится всем подписчикам.";
$lang['newsletter_create'] = "Создать письмо";
$lang['newsletter_date'] = "Дата";
$lang['newsletter_subject'] = "Тема письма";
$lang['newsletter_status'] = "Действия";
$lang['newsletter_sent'] = "Отосланные";
$lang['newsletter_action'] = "Действие";
$lang['newsletter_edit'] = "Редактировать";
$lang['newsletter_delete'] = "Удалить";
$lang['newsletter_send'] = "Отослать";
$lang['newsletter_sending'] = "Отсылка письма всем подписчикам....Пожалуйста подождите.<br>";
$lang['newsletter_sending_done1'] = "<b>Расылка закончена!</b>Отослано ";
$lang['newsletter_sending_done2'] = " письмо ";
$lang['newsletter_sending_done3'] = " подписчикам.<br>";
$lang['newsletter_sending_done4'] = "<span style='color:red'>Ошибка!!!<br>Возможно у вас указаны неверные настройки.</span>";
$lang['newsletter_edit_header'] = "Здесь вы можете создать письмо для рассылки.";
$lang['newsletter_edit_subject'] = "Тема:";
$lang['newsletter_edit_save'] = "Сохранить письмо";
$lang['newsletter_edit_update'] = "Письмо успешно обновлено!";
$lang['newsletter_edit_delete'] = "Письмо удалено!";
$lang['newsletter_edit_create'] = "Письмо создано!";

$lang['subscriber_noposts'] = "Нет зарегистрированных подписчиков.";
$lang['subscriber_delete_alert'] = "Вы правда хотите удалить:";
$lang['subscriber_edit_header'] = "Редактируйте информацию о подписчиках здесь.";
# ---
$lang['subscriber_add_header'] = "Добавление подписчика";
$lang['subscriber_edit_header'] = "Редактирование подписчика";
# ---
$lang['subscriber_created'] = "Создано";
$lang['subscriber_firstname'] = "Имя";
$lang['subscriber_lastname'] = "Фамилия";
$lang['subscriber_email'] = "E-mail адрес";
# ---
$lang['subscriber_firstname_txt'] = "Введите имя";
$lang['subscriber_lastname_txt'] = "Введите фамилию";
$lang['subscriber_email_txt'] = "Укажите E-mail адрес";
# ---
$lang['subscriber_action'] = "Действие";
$lang['subscriber_edit_action'] = "Действие";
$lang['subscriber_edit_save'] = "Обновить информацию";
$lang['subscriber_edit_update'] = "Информация обновлена!";
$lang['subscriber_edit_delete'] = "Подписчик удален!";

$lang['config_header'] = "Редактирование настроек.";
$lang['config_mail'] = "Метод отправки";
$lang['config_mail_description'] = "Различные методы отправки. При выборе SMTP заполните поля ниже.";
$lang['config_smtp'] = "SMTP сервер";
$lang['config_smtp_description'] = "Можно узнать у провайдера, какой у вас SMTP сервер.";
$lang['config_auth'] = "SMTP аутентификация обязательна?";
$lang['config_auth_description'] = "Используется только с методом почты SMTP. Требуется SMTP имя пользователя и пароль.";
$lang['config_true'] = "Да";
$lang['config_false'] = "Нет";
$lang['config_authuser'] = "SMTP имя пользователя";
$lang['config_authuser_description'] = "Имя пользователя для SMTP аутентификации. Обычно как и для вашего адреса электронной почты.";
$lang['config_authpassword'] = "SMTP пароль";
$lang['config_authpassword_description'] = "Пароль для SMTP аутентификации. Обычно как и для вашего адреса электронной почты.";
$lang['config_sendername'] = "Имя отправителя";
$lang['config_sendername_description'] = "Имя, которое появляется в электронной почте, как имя отправителя.";
$lang['config_senderemail'] = "E-mail отправителя";
$lang['config_senderemail_description'] = "Адрес электронной почты, который появляется в письме.";
$lang['config_lang_website'] = "Язык - фроненд";
$lang['config_lang_website_description'] = "Язык для формы подписки на сайте.";
$lang['config_lang_manager'] = "Язык - бэкенд";
$lang['config_lang_manager_description'] = "Язык, использующийся в системе управления.";
$lang['config_save'] = "Сохранить настройки";
$lang['config_update'] = "Настройки обновлены!";

$lang['cat_choose'] = 'Выберите категорию';
$lang['cat_add'] = 'Добавление категории';
$lang['cat_edit'] = 'Редактирование категории';
$lang['existing_categories'] = 'Существующие категории';

$lang['letter_base_title'] = 'Письмо без имени';
$lang['letter_base_body'] = 'Здесь сверстанный макет письма или содержимое';
$lang['letters_list_title'] = 'Письма, имеющиеся в системе';
$lang['letters_empty'] = 'В системе нет еще ни одного письма';
$lang['letter_title'] = 'Название письма-рассылки';
$lang['letter_create'] = 'Создание нового письма';
$lang['letter_edit'] = 'Редактирование письма';
$lang['letter_body'] = 'Текст письма';

$lang['links_templates'] = 'Шаблоны';
$lang['template_code'] = 'Код шаблона';
$lang['tpl_create'] = 'Создание нового шаблона';
$lang['tpl_edit'] = 'Редактирование шаблона';
$lang['template_code_info'] = 'В коде шаблона необходимо использовать <b>{{ content|raw }}</b> переменную для вывода тела самого письма.<br> Не забывайте об этом!';
$lang['not_choose'] = 'Не выбрано';
$lang['template_txt'] = 'Шаблон письма';
$lang['letter_edit'] = 'Редактирование письма';
$lang['preview'] = 'Просмотр';
$lang['delete'] = 'Удаление';
$lang['delete_template'] = 'Вы уверены, что хотите удалить этот шаблон? Удаление шаблона сбросит назначенный шаблон для всех писем.';
$lang['delete_letter'] = 'Вы уверены, что хотите удалить это письмо?';
$lang['delete_category'] = 'Вы уверены, что хотите удалить выбранную категорию? Удаление категории очистит привязку подписчиков и писем к этой категории.';
$lang['delete_subscriber'] = 'Вы уверены, что хотите удалить подписчика?';
$lang['create'] = 'Создание';
$lang['close'] = 'Закрыть';
$lang['save'] = 'Сохранить';
$lang['title'] = 'Заголовок';
$lang['description'] = 'Описание';
$lang['filter_by_cat'] = 'Фильтр по категории';
$lang['logs'] = 'Отчеты';
$lang['sendLetter'] = 'Отправка письма подписчикам';
$lang['send_complete'] = 'Отправка письма завершена успешно.';
$lang['send_prosess'] = 'Отправка письма...';
$lang['send_still_prosess'] = 'Все еще отправляем письмо...';

$lang['conf_mail'] = 'Конфигурация почты';
$lang['emailsender'] = 'Отправитель';
$lang['email_method'] = 'Метод отправки почты';
$lang['smtp_auth'] ='Авторизация SMTP';
$lang['smtp_auth_true'] ='<span style="color: green">Включена</span>';
$lang['smtp_auth_false'] ='<span style="color: red">Включена</span>';
$lang['smtp_port'] ='Порт SMTP';
$lang['smtp_host'] ='Адрес сервера SMTP';
$lang['smtp_username'] ='Имя пользователя';
$lang['smtp_pass'] ='Пароль пользователя';

$lang['conf_info']='Настройки отправки почты подгружается из конфигурации системы.';

$lang['subscriber_exist'] = 'Такой подписчик уже существует!';
$lang['error'] = 'Что-то пошло не так, укажите правильно данные!';
$lang['thankyou'] = 'Вы успешно подписались на новостную рассылку';
$lang['wait_unset_email'] = 'На вашу почту отправлено письмо со ссылкой, перейдя по которой вы можете отписаться от новостной рассылки';
$lang['subject_unset'] = 'Как жаль, что вы нас покидаете';
$lang['unbsuscribe_success'] = 'Вы успешно отписались от рассылки';
