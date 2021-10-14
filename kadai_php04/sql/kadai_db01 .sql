-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- ホスト: localhost:8889
-- 生成日時: 2021 年 10 月 07 日 12:57
-- サーバのバージョン： 5.7.34
-- PHP のバージョン: 7.4.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- データベース: `kadai_db01`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `kadai_an_table`
--

CREATE TABLE `kadai_an_table` (
  `id` int(12) NOT NULL,
  `anken` varchar(20) NOT NULL,
  `tanto` varchar(64) NOT NULL,
  `koumoku` varchar(64) NOT NULL,
  `suuryou` int(11) NOT NULL,
  `tanka` int(11) NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- テーブルの構造 `login_table`
--

CREATE TABLE `login_table` (
  `hogeUser` varchar(20) DEFAULT NULL,
  `hogehoge` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- テーブルの構造 `userDeta`
--

CREATE TABLE `userDeta` (
  `id` int(11) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `userDeta`
--

INSERT INTO `userDeta` (`id`, `email`, `password`, `created`) VALUES
(1, 'test3@test.com', '$2y$10$.X2dzMZfQNVCE62GwJpku.RbTu58EVKbn7A5.SByGcYmsqfdLbPiW', '2021-10-06 14:47:09');

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `kadai_an_table`
--
ALTER TABLE `kadai_an_table`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `userDeta`
--
ALTER TABLE `userDeta`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- ダンプしたテーブルの AUTO_INCREMENT
--

--
-- テーブルの AUTO_INCREMENT `kadai_an_table`
--
ALTER TABLE `kadai_an_table`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- テーブルの AUTO_INCREMENT `userDeta`
--
ALTER TABLE `userDeta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
