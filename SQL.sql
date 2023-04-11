-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Апр 10 2023 г., 13:49
-- Версия сервера: 5.7.27-30
-- Версия PHP: 7.1.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `u0830623_simple_stat`
--

-- --------------------------------------------------------

--
-- Структура таблицы `access`
--

CREATE TABLE `access` (
  `id` int(4) NOT NULL,
  `login` varchar(30) DEFAULT NULL,
  `password` varchar(30) DEFAULT NULL,
  `role` int(9) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `user_hash` varchar(32) DEFAULT NULL,
  `user_ip` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `access`
--

INSERT INTO `access` (`id`, `login`, `password`, `role`, `name`, `user_hash`, `user_ip`) VALUES
(1, 'admin', 'admin', 1, 'Админ', 'DHyIKlEirY', 2147483647),
(40, 'DEMO', 'DEMO', 0, 'DEMO', 'nNpgwwfDFr', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `accounts`
--

CREATE TABLE `accounts` (
  `id` int(11) NOT NULL,
  `yandex` varchar(30) DEFAULT NULL,
  `google` varchar(30) DEFAULT NULL,
  `google_id` varchar(30) DEFAULT NULL,
  `ya_counter` int(20) DEFAULT NULL,
  `vk` varchar(30) DEFAULT NULL,
  `fb` varchar(30) DEFAULT NULL,
  `elama` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `accounts`
--

INSERT INTO `accounts` (`id`, `yandex`, `google`, `google_id`, `ya_counter`, `vk`, `fb`, `elama`) VALUES
(1, 'admin', 'admin', 'admin', 123456789, 'vk_adm', 'fb_admin', NULL),
(32, 'DEMO', NULL, NULL, 321654, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `bots`
--

CREATE TABLE `bots` (
  `bot_id` int(11) NOT NULL,
  `login` varchar(256) DEFAULT NULL,
  `token` varchar(1024) NOT NULL,
  `name` varchar(256) DEFAULT NULL,
  `first_block_id` int(11) DEFAULT NULL,
  `current_block` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `bots`
--

INSERT INTO `bots` (`bot_id`, `login`, `token`, `name`, `first_block_id`, `current_block`) VALUES
(1, 'DEMO', '332888', 'Тестовый бот', NULL, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `bot_blocks`
--

CREATE TABLE `bot_blocks` (
  `block_id` int(11) NOT NULL,
  `bot_id` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `message` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `time` int(11) DEFAULT NULL,
  `manager` varchar(256) DEFAULT NULL,
  `next_block_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `bot_blocks`
--

INSERT INTO `bot_blocks` (`block_id`, `bot_id`, `type`, `message`, `time`, `manager`, `next_block_id`) VALUES
(7, 1, 2, 'Привет!  %F0%9F%91%8B%F0%9F%91%8B%F0%9F%91%8B\r\nЯ бот Ягода групп. Выберите действие%F0%9F%91%87:', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `bot_blocks_menu_items`
--

CREATE TABLE `bot_blocks_menu_items` (
  `block_id` int(11) NOT NULL,
  `button` varchar(256) NOT NULL,
  `next_block_id` int(11) DEFAULT NULL,
  `button_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `bot_blocks_menu_items`
--

INSERT INTO `bot_blocks_menu_items` (`block_id`, `button`, `next_block_id`, `button_id`) VALUES
(7, 'Список услуг', 10, 12),
(7, 'Контакт менеджера', 8, 13),
(7, 'Перейти на сайт', 9, 14);

-- --------------------------------------------------------

--
-- Структура таблицы `bot_types`
--

CREATE TABLE `bot_types` (
  `type_id` int(11) NOT NULL,
  `type_name` varchar(256) NOT NULL,
  `type_description` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `bot_types`
--

INSERT INTO `bot_types` (`type_id`, `type_name`, `type_description`) VALUES
(1, 'message', 'Сообщение'),
(2, 'menu', 'Меню'),
(3, 'time', 'Задержка'),
(4, 'to_manager', 'Переход к менеджеру'),
(5, 'query', 'Запрос данных');

-- --------------------------------------------------------

--
-- Структура таблицы `bot_users`
--

CREATE TABLE `bot_users` (
  `bot_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_login` varchar(256) DEFAULT NULL,
  `user_name` varchar(256) DEFAULT NULL,
  `chat_id` int(32) NOT NULL,
  `phone` varchar(256) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `bot_users_data`
--

CREATE TABLE `bot_users_data` (
  `data_id` int(11) NOT NULL,
  `bot_id` int(11) NOT NULL,
  `chat_id` int(11) NOT NULL,
  `date` date DEFAULT NULL,
  `steps` mediumtext
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--

--

CREATE TABLE `data_admin` (
  `id` int(11) NOT NULL,
  `date` date DEFAULT NULL,
  `source` varchar(20) DEFAULT NULL,
  `medium` varchar(20) DEFAULT NULL,
  `campaign` varchar(20) DEFAULT NULL,
  `content` varchar(20) DEFAULT NULL,
  `term` varchar(20) DEFAULT NULL,
  `device` varchar(50) DEFAULT NULL,
  `clicks` int(20) DEFAULT NULL,
  `goals` int(20) DEFAULT NULL,
  `cost` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `data_DEMO`
--

CREATE TABLE `data_DEMO` (
  `id` int(11) NOT NULL,
  `date` date DEFAULT NULL,
  `source` varchar(50) DEFAULT NULL,
  `medium` varchar(20) DEFAULT NULL,
  `campaign` varchar(50) DEFAULT NULL,
  `content` varchar(50) DEFAULT NULL,
  `term` varchar(200) DEFAULT NULL,
  `device` varchar(50) DEFAULT NULL,
  `clicks` int(20) DEFAULT NULL,
  `goals` int(20) DEFAULT NULL,
  `cost` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Структура таблицы `goals`
--

CREATE TABLE `goals` (
  `id` int(11) NOT NULL,
  `yandex` varchar(30) DEFAULT NULL,
  `ya_goal` int(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `goals`
--

-- --------------------------------------------------------

--
-- Структура таблицы `quiz`
--

CREATE TABLE `quiz` (
  `quiz_id` int(11) NOT NULL,
  `login` varchar(256) NOT NULL,
  `name` varchar(256) NOT NULL,
  `domain` varchar(256) NOT NULL,
  `setting` text,
  `other_setting` text,
  `background` text,
  `logo` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `quiz`
--

INSERT INTO `quiz` (`quiz_id`, `login`, `name`, `domain`, `setting`, `other_setting`, `background`, `logo`) VALUES
(16, 'DEMO', 'Тест 2', 'https://stat.yagoda-group.ru', '[{\"sort_order\":\"0\",\"name\":\"Откуда вы узнали о нас?\",\"answers\":[{\"name\":\"VK\",\"type\":\"2\",\"next\":\"1\"},{\"name\":\"Insta\",\"type\":\"2\",\"next\":\"1\"},{\"name\":\"Сайт\",\"type\":\"2\",\"next\":\"1\"},{\"name\":\"Другое\",\"type\":\"2\",\"next\":\"1\"},{\"name\":\"не помню\",\"type\":\"2\",\"next\":\"1\"}]},{\"sort_order\":\"1\",\"name\":\"Как часто вы посещаете наш магазин ? \",\"answers\":[{\"name\":\"1 раз в месяц\",\"type\":\"0\",\"next\":\"2\"},{\"name\":\"Перед 1 сентября\",\"type\":\"0\",\"next\":\"2\"}]},{\"sort_order\":\"2\",\"name\":\"Какие новые товары вы хотели видеть в нашем ассортименте?\",\"answers\":[{\"name\":\"Укажите несколько товаров\",\"type\":\"0\",\"next\":\"3\"},{\"name\":\"Написать несколько товаров\",\"type\":\"1\",\"next\":\"3\"}]},{\"sort_order\":\"3\",\"name\":\"У вас есть карта?\",\"answers\":[{\"name\":\"да\",\"type\":\"0\",\"next\":\"5\"},{\"name\":\"нет\",\"type\":\"0\",\"next\":\"4\"}]},{\"sort_order\":\"4\",\"name\":\"Хотите получить?\",\"answers\":[{\"name\":\"Да\",\"type\":\"0\",\"next\":\"5\"},{\"name\":\"Нет\",\"type\":\"0\",\"next\":\"5\"}]}]', '{\"redirect\":\"https://yagoda-group.ru\",\"description\":\"Ответьте на 5 вопросов и получите скидку 10% на все услуги!\",\"color\":\"#50be71\",\"text_color\":\"#fff\",\"policy\":\"https://yagoda-group.ru/privacy/\"}', 'https://stat.yagoda-group.ru/images/depositphotos_123650766-stock-illustration-glowing-lines-with-dots.jpg', 'https://stat.yagoda-group.ru/images/лого ИИ-бел.png');

-- --------------------------------------------------------

--
-- Структура таблицы `quiz_data`
--

CREATE TABLE `quiz_data` (
  `data_id` int(11) NOT NULL,
  `name` varchar(256) DEFAULT NULL,
  `phone` varchar(256) DEFAULT NULL,
  `date_added` date NOT NULL,
  `text` text,
  `quiz_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `quiz_data`
--

INSERT INTO `quiz_data` (`data_id`, `name`, `phone`, `date_added`, `text`, `quiz_id`) VALUES
(1, 'Тест1', '88001231212', '2023-03-12', '{\"radio_0\":\"Другое\",\"radio_1\":\"Перед 1 сентября\",\"radio_2\":\"Написать несколько товаров\",\"text_2\":\"\",\"radio_3\":\"нет\",\"quiz_name\":\"Тест1\",\"quiz_phone\":\"88001231212\",\"quiz_id\":\"16\"}', 16),
(2, 'Тест2', '88001231212', '2023-03-12', '{\"radio_0\":\"Другое\",\"radio_1\":\"Перед 1 сентября\",\"radio_2\":\"Укажите несколько товаров\",\"text_2\":\"\",\"radio_3\":\"да\",\"quiz_name\":\"Тест2\",\"quiz_phone\":\"88001231212\",\"quiz_id\":\"16\"}', 16),
(3, 'Тест4', '88001234543', '2023-03-12', '{\"radio_0\":\"Сайт\",\"radio_1\":\"Перед 1 сентября\",\"radio_2\":\"Написать несколько товаров\",\"text_2\":\"\",\"radio_3\":\"нет\",\"quiz_name\":\"Тест4\",\"quiz_phone\":\"88001234543\",\"quiz_id\":\"16\"}', 16),
(4, 'Тест1', '89194671538', '2023-04-09', '{\"checkbox_0\":\"\",\"radio_1\":\"Перед 1 сентября\",\"radio_2\":\"Укажите несколько товаров\",\"text_2\":\"\",\"radio_3\":\"да\",\"quiz_name\":\"Тест1\",\"quiz_phone\":\"89194671538\",\"quiz_id\":\"16\",\"redirect\":\"https://yagoda-group.ru\"}', 16);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `access`
--
ALTER TABLE `access`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `login` (`login`);

--
-- Индексы таблицы `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `yandex` (`yandex`);

--
-- Индексы таблицы `bots`
--
ALTER TABLE `bots`
  ADD PRIMARY KEY (`bot_id`);

--
-- Индексы таблицы `bot_blocks`
--
ALTER TABLE `bot_blocks`
  ADD PRIMARY KEY (`block_id`);

--
-- Индексы таблицы `bot_blocks_menu_items`
--
ALTER TABLE `bot_blocks_menu_items`
  ADD PRIMARY KEY (`button_id`);

--
-- Индексы таблицы `bot_types`
--
ALTER TABLE `bot_types`
  ADD PRIMARY KEY (`type_id`);

--
-- Индексы таблицы `bot_users`
--
ALTER TABLE `bot_users`
  ADD PRIMARY KEY (`user_id`);

--
-- Индексы таблицы `bot_users_data`
--
ALTER TABLE `bot_users_data`
  ADD PRIMARY KEY (`data_id`);

--
-- Индексы таблицы `data_admin`
--
ALTER TABLE `data_admin`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `data_DEMO`
--
ALTER TABLE `data_DEMO`
  ADD PRIMARY KEY (`id`);
--
-- Индексы таблицы `goals`
--
ALTER TABLE `goals`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `quiz`
--
ALTER TABLE `quiz`
  ADD PRIMARY KEY (`quiz_id`);

--
-- Индексы таблицы `quiz_data`
--
ALTER TABLE `quiz_data`
  ADD PRIMARY KEY (`data_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `access`
--
ALTER TABLE `access`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT для таблицы `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT для таблицы `bots`
--
ALTER TABLE `bots`
  MODIFY `bot_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT для таблицы `bot_blocks`
--
ALTER TABLE `bot_blocks`
  MODIFY `block_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT для таблицы `bot_blocks_menu_items`
--
ALTER TABLE `bot_blocks_menu_items`
  MODIFY `button_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=109;

--
-- AUTO_INCREMENT для таблицы `bot_types`
--
ALTER TABLE `bot_types`
  MODIFY `type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `bot_users`
--
ALTER TABLE `bot_users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT для таблицы `bot_users_data`
--
ALTER TABLE `bot_users_data`
  MODIFY `data_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=332;

-- AUTO_INCREMENT для таблицы `data_admin`
--
ALTER TABLE `data_admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=882;

--
-- AUTO_INCREMENT для таблицы `data_DEMO`
--
ALTER TABLE `data_DEMO`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97438;

--
--
-- AUTO_INCREMENT для таблицы `goals`
--
ALTER TABLE `goals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=125;

--
-- AUTO_INCREMENT для таблицы `quiz`
--
ALTER TABLE `quiz`
  MODIFY `quiz_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT для таблицы `quiz_data`
--
ALTER TABLE `quiz_data`
  MODIFY `data_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `accounts`
--
ALTER TABLE `accounts`
  ADD CONSTRAINT `accounts_ibfk_1` FOREIGN KEY (`yandex`) REFERENCES `access` (`login`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
