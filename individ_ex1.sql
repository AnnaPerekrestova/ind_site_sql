-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Сен 27 2021 г., 12:43
-- Версия сервера: 10.1.36-MariaDB
-- Версия PHP: 7.2.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `individ_ex1`
--

-- --------------------------------------------------------

--
-- Структура таблицы `accruals`
--

CREATE TABLE `accruals` (
  `map_nom` varchar(10) NOT NULL,
  `customer_phone` varchar(10) NOT NULL,
  `period_start` date NOT NULL,
  `period_end` date NOT NULL,
  `summa` decimal(10,2) DEFAULT NULL,
  `accrued` decimal(10,2) DEFAULT NULL,
  `compensation` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `accruals`
--

INSERT INTO `accruals` (`map_nom`, `customer_phone`, `period_start`, `period_end`, `summa`, `accrued`, `compensation`) VALUES
('1', '88-88-88', '2019-05-01', '2019-05-31', '281.00', '281.00', '0.00'),
('2', '12-34-56', '2019-05-01', '2019-05-31', '180.65', '200.00', '19.35');

--
-- Триггеры `accruals`
--
DELIMITER $$
CREATE TRIGGER `acc_upd` BEFORE UPDATE ON `accruals` FOR EACH ROW BEGIN
	DECLARE days int;
    DECLARE mon int;
    DECLARE cur_sum DECIMAL;
    DECLARE val DECIMAL;
    DECLARE exc DECIMAL;
    
    SET cur_sum = (SELECT sum(c.summa) FROM calls c WHERE c.date_ring BETWEEN NEW.period_start AND NEW.period_end AND c.customer_phone=NEW.customer_phone);    
    IF cur_sum IS NULL THEN
    	SET cur_sum=0;
    END IF;
    SET val = (SELECT p.value FROM phone p WHERE NEW.customer_phone=p.customer_phone);    
    SET NEW.accrued = val + cur_sum;
    SET mon = MONTH(NEW.period_start);
   

    SET days = (SELECT sum(DATEDIFF(r.date_repair,r.date_claim)+1)
    FROM repairs r  INNER JOIN phone p ON p.customer_phone = r.customer_phone
    WHERE NEW.customer_phone = p.customer_phone AND MONTH(r.date_repair)=mon);
    IF days IS NULL THEN
    	SET days=0;
    END IF;
    SET exc =(SELECT distinct ex.exempt FROM 
               phone p INNER JOIN customer cus ON cus.customer_id=p.customer_id 
               INNER JOIN exempt_table ex ON cus.customer_type=ex.exempt_type 				 				WHERE NEW.customer_phone=p.customer_phone );
    SET NEW.compensation= (val + cur_sum)*(1-exc)+days*val*exc/(DATEDIFF(NEW.period_end,NEW.period_start)+1);
    SET NEW.summa = NEW.accrued-NEW.compensation;

END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `accruals_trig` BEFORE INSERT ON `accruals` FOR EACH ROW BEGIN
	DECLARE days int;
    DECLARE mon int;
    DECLARE cur_sum DECIMAL;
    DECLARE val DECIMAL;
    DECLARE exc DECIMAL;
    
    SET cur_sum = (SELECT sum(c.summa) FROM calls c WHERE c.date_ring BETWEEN NEW.period_start AND NEW.period_end AND c.customer_phone=NEW.customer_phone);    
    IF cur_sum IS NULL THEN
    	SET cur_sum=0;
    END IF;
    SET val = (SELECT p.value FROM phone p WHERE NEW.customer_phone=p.customer_phone);    
    SET NEW.accrued = val + cur_sum;
    SET mon = MONTH(NEW.period_start);
   

    SET days = (SELECT sum(DATEDIFF(r.date_repair,r.date_claim)+1)
    FROM repairs r  INNER JOIN phone p ON p.customer_phone = r.customer_phone
    WHERE NEW.customer_phone = p.customer_phone AND MONTH(r.date_repair)=mon);
    IF days IS NULL THEN
    	SET days=0;
    END IF;
    SET exc =(SELECT distinct ex.exempt FROM 
               phone p INNER JOIN customer cus ON cus.customer_id=p.customer_id 
               INNER JOIN exempt_table ex ON cus.customer_type=ex.exempt_type 				 				WHERE NEW.customer_phone=p.customer_phone );
    SET NEW.compensation= (val + cur_sum)*(1-exc)+days*val*exc/(DATEDIFF(NEW.period_end,NEW.period_start)+1);
    SET NEW.summa = NEW.accrued-NEW.compensation;

END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Структура таблицы `bank`
--

CREATE TABLE `bank` (
  `bank_id` int(4) NOT NULL,
  `bank` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `bank`
--

INSERT INTO `bank` (`bank_id`, `bank`) VALUES
(1, 'Сбербанк'),
(2, 'Тинькофф банк'),
(3, 'Райффайзенбанк');

-- --------------------------------------------------------

--
-- Структура таблицы `calls`
--

CREATE TABLE `calls` (
  `call_id` int(4) NOT NULL,
  `customer_phone` varchar(10) NOT NULL,
  `date_ring` date NOT NULL,
  `ring_type` tinyint(1) NOT NULL,
  `number` varchar(10) DEFAULT NULL,
  `country` varchar(15) DEFAULT NULL,
  `town` varchar(15) DEFAULT NULL,
  `value_min` int(3) NOT NULL,
  `summa_min` decimal(10,2) DEFAULT NULL,
  `summa` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `calls`
--

INSERT INTO `calls` (`call_id`, `customer_phone`, `date_ring`, `ring_type`, `number`, `country`, `town`, `value_min`, `summa_min`, `summa`) VALUES
(28, '88-88-88', '2019-05-21', 0, '23-24-25', 'Россия', 'Москва', 3, '2.40', '7.20'),
(29, '88-88-88', '2019-05-29', 1, '34-35-36', 'Россия', 'Белгород', 3, '8.00', '24.00'),
(39, '12-34-56', '2019-06-08', 0, '324234', 'Россия', 'Москва', 5, '3.00', '15.00'),
(40, '88-88-88', '2019-06-09', 1, '1212121121', '', '', 2, '10.00', '20.00'),
(41, '88-88-88', '2019-06-06', 1, '324567', '', '', 4, '10.00', '40.00'),
(44, '65-98-78', '2019-06-03', 0, '645311', 'Россия', 'Москва', 5, '3.00', '15.00'),
(45, '12-34-56', '2019-06-14', 1, '346758', 'Россия', 'Волгоград', 10, '10.00', '100.00'),
(46, '65-98-78', '2019-06-14', 0, '234568', 'Россия', 'Москва', 5, '3.00', '15.00'),
(47, '65-98-78', '2019-06-14', 1, '345362', 'Россия', 'Мурманск', 22, '10.00', '220.00'),
(48, '65-98-78', '2019-06-14', 0, '9878654', 'Россия', 'Москва', 22, '3.00', '66.00'),
(49, '88-88-88', '2020-06-01', 0, '5555555', 'Россия', 'Москва', 2, '3.00', '6.00'),
(50, '65-98-78', '2020-06-01', 0, '345343', 'Россия', 'Москва', 5, '3.00', '15.00');

--
-- Триггеры `calls`
--
DELIMITER $$
CREATE TRIGGER `calls_upd` BEFORE UPDATE ON `calls` FOR EACH ROW BEGIN
    DECLARE t DECIMAL(10, 2);
    DECLARE lim DECIMAL;
    DECLARE cur_sum DECIMAl;
SET lim = (SELECT p.limit_value FROM phone p WHERE p.customer_phone=NEW.customer_phone);

    SET t = (SELECT rate from rates where ring_type=NEW.ring_type);
        
    SET NEW.summa_min = t;
    SET NEW.summa = NEW.summa_min*NEW.value_min;
    SET cur_sum = (SELECT sum(c.summa) FROM calls c WHERE c.customer_phone=NEW.customer_phone AND MONTH(c.date_ring)=MONTH(NEW.date_ring));
    IF cur_sum>lim THEN
    DELETE FROM calls WHERE calls.call_id=NEW.call_id;
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `summa_min` BEFORE INSERT ON `calls` FOR EACH ROW BEGIN
    DECLARE t DECIMAL(10, 2);
    DECLARE lim DECIMAL;
    DECLARE cur_sum DECIMAl;
SET lim = (SELECT p.limit_value FROM phone p WHERE p.customer_phone=NEW.customer_phone);

    SET t = (SELECT rate from rates where ring_type=NEW.ring_type);
        
    SET NEW.summa_min = t;
    SET NEW.summa = NEW.summa_min*NEW.value_min;
    SET cur_sum = (SELECT sum(c.summa) FROM calls c WHERE c.customer_phone=NEW.customer_phone AND MONTH(c.date_ring)=MONTH(NEW.date_ring));
    IF cur_sum>lim THEN
    DELETE FROM calls WHERE calls.call_id=NEW.call_id;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Структура таблицы `customer`
--

CREATE TABLE `customer` (
  `customer_id` int(4) NOT NULL,
  `customer_type` tinyint(1) NOT NULL,
  `customer_fio` varchar(60) DEFAULT NULL,
  `customer_name` varchar(60) DEFAULT NULL,
  `customer_room` varchar(13) DEFAULT NULL,
  `chief` varchar(40) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `customer`
--

INSERT INTO `customer` (`customer_id`, `customer_type`, `customer_fio`, `customer_name`, `customer_room`, `chief`) VALUES
(4, 0, 'Иванов Иван Сергеевич', '', '12', ''),
(5, 1, NULL, 'Учет', '17', 'Петров В.В.'),
(7, 0, 'Васечкин Василий Викторович', '', '18', '');

-- --------------------------------------------------------

--
-- Структура таблицы `exempt_table`
--

CREATE TABLE `exempt_table` (
  `exempt_type` tinyint(1) NOT NULL,
  `name` text NOT NULL,
  `exempt` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `exempt_table`
--

INSERT INTO `exempt_table` (`exempt_type`, `name`, `exempt`) VALUES
(0, 'Физическое лицо', '0.80'),
(1, 'Юридическое лицо', '1.00');

-- --------------------------------------------------------

--
-- Структура таблицы `payment`
--

CREATE TABLE `payment` (
  `customer_phone` varchar(10) NOT NULL,
  `map_nom` varchar(10) NOT NULL,
  `date_map` varchar(60) NOT NULL,
  `map_count` decimal(10,2) NOT NULL,
  `account` varchar(20) NOT NULL,
  `bank_id` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `payment`
--

INSERT INTO `payment` (`customer_phone`, `map_nom`, `date_map`, `map_count`, `account`, `bank_id`) VALUES
('12-34-56', '2', '2019-05-01', '700.00', '1526374859607', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `phone`
--

CREATE TABLE `phone` (
  `customer_phone` varchar(10) NOT NULL,
  `customer_id` int(4) NOT NULL,
  `limit_value` decimal(10,2) NOT NULL,
  `phone_address` varchar(60) NOT NULL,
  `value` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `phone`
--

INSERT INTO `phone` (`customer_phone`, `customer_id`, `limit_value`, `phone_address`, `value`) VALUES
('12-34-56', 5, '1000.00', 'Щербаковская 38, к.10', '200.00'),
('65-98-78', 4, '900.00', 'Щербаковская 38, к.12', '220.00'),
('88-88-88', 4, '900.00', 'Щербаковская 38, к.12', '250.00');

-- --------------------------------------------------------

--
-- Структура таблицы `rates`
--

CREATE TABLE `rates` (
  `ring_type` tinyint(1) NOT NULL,
  `name` text NOT NULL,
  `rate` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `rates`
--

INSERT INTO `rates` (`ring_type`, `name`, `rate`) VALUES
(0, 'Внутренний', '3.00'),
(1, 'Междугородний', '10.00');

-- --------------------------------------------------------

--
-- Структура таблицы `repairs`
--

CREATE TABLE `repairs` (
  `number_claim` int(5) NOT NULL,
  `date_claim` date NOT NULL,
  `date_repair` date NOT NULL,
  `inspector` varchar(15) DEFAULT NULL,
  `customer_phone` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `repairs`
--

INSERT INTO `repairs` (`number_claim`, `date_claim`, `date_repair`, `inspector`, `customer_phone`) VALUES
(2, '2019-05-28', '2019-05-30', NULL, '12-34-56');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `user_id` int(11) UNSIGNED NOT NULL,
  `user_login` varchar(30) NOT NULL,
  `user_password` varchar(32) NOT NULL,
  `user_salt` varchar(32) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`user_id`, `user_login`, `user_password`, `user_salt`) VALUES
(1, 'ann', 'abd51e57fd4c3e2016106d9b3f47e3b1', 's3i'),
(2, 'nikita', 'a42ea09b3ccd667426869c75009c2ae4', '1dp'),
(3, 'anna', '3df5e9242f05ffa368554ffc6979795b', '1h1');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `accruals`
--
ALTER TABLE `accruals`
  ADD PRIMARY KEY (`map_nom`),
  ADD KEY `customer_phone` (`customer_phone`);

--
-- Индексы таблицы `bank`
--
ALTER TABLE `bank`
  ADD PRIMARY KEY (`bank_id`);

--
-- Индексы таблицы `calls`
--
ALTER TABLE `calls`
  ADD PRIMARY KEY (`call_id`),
  ADD KEY `customer_phone` (`customer_phone`),
  ADD KEY `ring_type` (`ring_type`);

--
-- Индексы таблицы `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`customer_id`),
  ADD KEY `customer_type` (`customer_type`);

--
-- Индексы таблицы `exempt_table`
--
ALTER TABLE `exempt_table`
  ADD PRIMARY KEY (`exempt_type`);

--
-- Индексы таблицы `payment`
--
ALTER TABLE `payment`
  ADD KEY `customer_phone` (`customer_phone`),
  ADD KEY `map_nom` (`map_nom`),
  ADD KEY `bank_id` (`bank_id`);

--
-- Индексы таблицы `phone`
--
ALTER TABLE `phone`
  ADD PRIMARY KEY (`customer_phone`),
  ADD KEY `customer_id` (`customer_id`) USING BTREE;

--
-- Индексы таблицы `rates`
--
ALTER TABLE `rates`
  ADD PRIMARY KEY (`ring_type`);

--
-- Индексы таблицы `repairs`
--
ALTER TABLE `repairs`
  ADD PRIMARY KEY (`number_claim`),
  ADD KEY `customer_phone` (`customer_phone`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `bank`
--
ALTER TABLE `bank`
  MODIFY `bank_id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `calls`
--
ALTER TABLE `calls`
  MODIFY `call_id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT для таблицы `customer`
--
ALTER TABLE `customer`
  MODIFY `customer_id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT для таблицы `repairs`
--
ALTER TABLE `repairs`
  MODIFY `number_claim` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `accruals`
--
ALTER TABLE `accruals`
  ADD CONSTRAINT `accruals_ibfk_1` FOREIGN KEY (`customer_phone`) REFERENCES `phone` (`customer_phone`);

--
-- Ограничения внешнего ключа таблицы `calls`
--
ALTER TABLE `calls`
  ADD CONSTRAINT `calls_ibfk_1` FOREIGN KEY (`customer_phone`) REFERENCES `phone` (`customer_phone`),
  ADD CONSTRAINT `calls_ibfk_2` FOREIGN KEY (`ring_type`) REFERENCES `rates` (`ring_type`);

--
-- Ограничения внешнего ключа таблицы `customer`
--
ALTER TABLE `customer`
  ADD CONSTRAINT `customer_ibfk_1` FOREIGN KEY (`customer_type`) REFERENCES `exempt_table` (`exempt_type`);

--
-- Ограничения внешнего ключа таблицы `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`customer_phone`) REFERENCES `phone` (`customer_phone`),
  ADD CONSTRAINT `payment_ibfk_2` FOREIGN KEY (`bank_id`) REFERENCES `bank` (`bank_id`),
  ADD CONSTRAINT `payment_ibfk_3` FOREIGN KEY (`map_nom`) REFERENCES `accruals` (`map_nom`);

--
-- Ограничения внешнего ключа таблицы `phone`
--
ALTER TABLE `phone`
  ADD CONSTRAINT `phone_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`);

--
-- Ограничения внешнего ключа таблицы `repairs`
--
ALTER TABLE `repairs`
  ADD CONSTRAINT `repairs_ibfk_1` FOREIGN KEY (`customer_phone`) REFERENCES `phone` (`customer_phone`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
