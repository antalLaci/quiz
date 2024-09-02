-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Gép: 127.0.0.1
-- Létrehozás ideje: 2023. Dec 08. 07:58
-- Kiszolgáló verziója: 10.4.28-MariaDB
-- PHP verzió: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Adatbázis: `project_testing`
--

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `question` text NOT NULL,
  `correct` int(11) NOT NULL,
  `answer1` text NOT NULL,
  `answer2` text NOT NULL,
  `answer3` text NOT NULL,
  `answer4` text NOT NULL,
  `point` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- A tábla adatainak kiíratása `questions`
--

INSERT INTO `questions` (`id`, `question`, `correct`, `answer1`, `answer2`, `answer3`, `answer4`, `point`) VALUES
(50, 'Mi a fővárosa Franciaországnak?', 2, 'Berlin', 'Párizs', 'Madrid', 'Róma', 10),
(51, 'Melyik évben kezdődött az első világháború?', 2, '1905', '1914', '1920', '1939', 10),
(52, 'Mi a világ legnagyobb óceánja?', 3, 'Atlanti-óceán', 'Indiai-óceán', 'Csendes-óceán', 'Északi-sarki-óceán', 10),
(53, 'Ki volt az első ember a Holdon?', 1, 'Neil Armstrong', 'Buzz Aldrin', 'Yuri Gagarin', 'John Glenn', 10),
(54, 'Melyik állam a skót területek része?', 4, 'Anglia', 'Írország', 'Wales', 'Skócia', 10),
(55, 'Ki írta a Rómeó és Júlia című tragédiát?', 3, 'Charles Dickens', 'Jane Austen', 'William Shakespeare', 'Leo Tolstoy', 10),
(56, 'Melyik növény az alapanyaga a teának?', 2, 'Kávébabb', 'Tealevél', 'Kakóbab', 'Szőlő', 10),
(57, 'Ki festette a Mona Lisát?', 1, 'Leonardo da Vinci', 'Vincent van Gogh', 'Pablo Picasso', 'Michelangelo', 10),
(58, 'Hány ország alkotja az Európai Uniót?', 4, '20', '15', '25', '27', 10),
(59, 'Mi a legmagasabb hegy a világon?', 3, 'Mount Everest', 'K2', 'Himalája', 'Matterhorn', 10);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `quizes`
--

CREATE TABLE `quizes` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `questionIds` text NOT NULL,
  `points` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- A tábla adatainak kiíratása `quizes`
--

INSERT INTO `quizes` (`id`, `name`, `questionIds`, `points`) VALUES
(37, 'Kvíz1', '50,51,52,53,54,55,56,57,58,59', 100);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `statistic`
--

CREATE TABLE `statistic` (
  `statisticId` int(11) NOT NULL,
  `playedQuiz` int(11) NOT NULL,
  `points` int(11) NOT NULL,
  `playerId` int(11) NOT NULL,
  `maxPoints` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

--
-- A tábla adatainak kiíratása `statistic`
--

INSERT INTO `statistic` (`statisticId`, `playedQuiz`, `points`, `playerId`, `maxPoints`) VALUES
(20, 1, 90, 1, 100);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `users`
--

CREATE TABLE `users` (
  `Id` int(11) NOT NULL,
  `Username` text NOT NULL,
  `Password` text NOT NULL,
  `Name` text NOT NULL,
  `admin` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

--
-- A tábla adatainak kiíratása `users`
--

INSERT INTO `users` (`Id`, `Username`, `Password`, `Name`, `admin`) VALUES
(1, 'admin', 'admin', 'Adminisztrátor', 1),
(7, 'antallaszlo', 'antallaszlo', 'Antal László', 0),
(9, 'imreago', 'imreago', 'Ágó Imre', 0),
(10, 'imreago', 'imreago', 'Ágó Imre', 0),
(11, 'raczlaszlo', 'raczlaszlo', 'Rácz László', 0);

--
-- Indexek a kiírt táblákhoz
--

--
-- A tábla indexei `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`);

--
-- A tábla indexei `quizes`
--
ALTER TABLE `quizes`
  ADD PRIMARY KEY (`id`);

--
-- A tábla indexei `statistic`
--
ALTER TABLE `statistic`
  ADD PRIMARY KEY (`statisticId`);

--
-- A tábla indexei `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`Id`);

--
-- A kiírt táblák AUTO_INCREMENT értéke
--

--
-- AUTO_INCREMENT a táblához `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT a táblához `quizes`
--
ALTER TABLE `quizes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT a táblához `statistic`
--
ALTER TABLE `statistic`
  MODIFY `statisticId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT a táblához `users`
--
ALTER TABLE `users`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
