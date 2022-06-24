-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Värd: 127.0.0.1
-- Tid vid skapande: 24 jun 2022 kl 20:24
-- Serverversion: 10.4.21-MariaDB
-- PHP-version: 8.0.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databas: `apl`
--

-- --------------------------------------------------------

--
-- Tabellstruktur `anvandare`
--

CREATE TABLE `anvandare` (
  `id` int(11) NOT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT 0,
  `anvnamn` varchar(100) NOT NULL,
  `losenord` varchar(100) NOT NULL,
  `fnamn` varchar(100) NOT NULL,
  `enamn` varchar(100) NOT NULL,
  `foretagid` varchar(100) DEFAULT NULL,
  `hash` varchar(20) NOT NULL,
  `expire` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumpning av Data i tabell `anvandare`
--

INSERT INTO `anvandare` (`id`, `admin`, `anvnamn`, `losenord`, `fnamn`, `enamn`, `foretagid`, `hash`, `expire`) VALUES
(1, 0, 'kalle', 'kalle', 'Pelle', 'Persson', 'combitech', 'tt', '2022-06-23 11:42:19'),
(2, 0, 'stinus', 'stinus', 'Ulrika', 'Ohlsson', 'Farsight', '', NULL),
(4, 1, 'stefan', 'stefan', 'stefan', 'stefan', NULL, 'tt', '2022-06-24 11:28:16');

-- --------------------------------------------------------

--
-- Tabellstruktur `arbetsplats`
--

CREATE TABLE `arbetsplats` (
  `foretagsnamn` varchar(100) NOT NULL,
  `kontaktnummer` varchar(15) NOT NULL,
  `epost` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumpning av Data i tabell `arbetsplats`
--

INSERT INTO `arbetsplats` (`foretagsnamn`, `kontaktnummer`, `epost`) VALUES
('combitech', '050050', 'inf@combitech.se'),
('Farsight', '050500', 'infor@farsight.se');

-- --------------------------------------------------------

--
-- Tabellstruktur `elev`
--

CREATE TABLE `elev` (
  `pnr` varchar(13) NOT NULL,
  `fnamn` varchar(30) DEFAULT NULL,
  `enamn` varchar(100) DEFAULT NULL,
  `klass` varchar(10) DEFAULT NULL,
  `epost` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumpning av Data i tabell `elev`
--

INSERT INTO `elev` (`pnr`, `fnamn`, `enamn`, `klass`, `epost`) VALUES
('11', 'miss', 'missys', 'it3', 'mimi'),
('222', 'bertil', 'bsson', 'te4', 'bb'),
('333', 'ola', 'ohlsson', 'it3', 'ool'),
('555', 'Sten', 'stensson', 'it3', 'sten'),
('666', 'olle', 'ollsson', 'it3', 'oll'),
('999', 'aaron', 'bensson', 'te4', 'aab');

-- --------------------------------------------------------

--
-- Tabellstruktur `exkluderadearbetsdagar`
--

CREATE TABLE `exkluderadearbetsdagar` (
  `id` int(11) NOT NULL,
  `startdag` date NOT NULL,
  `slutdag` date DEFAULT NULL COMMENT 'om ej satt bara startdag'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellstruktur `narvarande`
--

CREATE TABLE `narvarande` (
  `pid` int(11) NOT NULL,
  `dag` date NOT NULL,
  `status` int(11) NOT NULL,
  `registreratdatum` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumpning av Data i tabell `narvarande`
--

INSERT INTO `narvarande` (`pid`, `dag`, `status`, `registreratdatum`) VALUES
(1, '2022-06-24', 2, '2022-06-24'),
(3, '2022-06-24', 2, '2022-06-24');

-- --------------------------------------------------------

--
-- Tabellstruktur `period`
--

CREATE TABLE `period` (
  `periodnamn` varchar(30) NOT NULL,
  `start` date NOT NULL,
  `slut` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumpning av Data i tabell `period`
--

INSERT INTO `period` (`periodnamn`, `start`, `slut`) VALUES
('ht22', '2022-09-19', '2022-10-28'),
('vt21', '2022-06-05', '2022-06-30');

-- --------------------------------------------------------

--
-- Tabellstruktur `placering`
--

CREATE TABLE `placering` (
  `id` int(11) NOT NULL,
  `personnummer` varchar(13) NOT NULL,
  `period` varchar(15) NOT NULL,
  `foretagsnamn` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumpning av Data i tabell `placering`
--

INSERT INTO `placering` (`id`, `personnummer`, `period`, `foretagsnamn`) VALUES
(1, '555', 'vt21', 'combitech'),
(2, '11', 'vt21', 'Farsight'),
(3, '11', 'ht22', 'combitech'),
(4, '999', 'vt21', 'combitech'),
(5, '666', 'ht22', 'combitech');

--
-- Index för dumpade tabeller
--

--
-- Index för tabell `anvandare`
--
ALTER TABLE `anvandare`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ftg` (`foretagid`);

--
-- Index för tabell `arbetsplats`
--
ALTER TABLE `arbetsplats`
  ADD PRIMARY KEY (`foretagsnamn`);

--
-- Index för tabell `elev`
--
ALTER TABLE `elev`
  ADD PRIMARY KEY (`pnr`);

--
-- Index för tabell `exkluderadearbetsdagar`
--
ALTER TABLE `exkluderadearbetsdagar`
  ADD PRIMARY KEY (`id`);

--
-- Index för tabell `narvarande`
--
ALTER TABLE `narvarande`
  ADD PRIMARY KEY (`pid`,`dag`);

--
-- Index för tabell `period`
--
ALTER TABLE `period`
  ADD PRIMARY KEY (`periodnamn`);

--
-- Index för tabell `placering`
--
ALTER TABLE `placering`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pnr` (`personnummer`),
  ADD KEY `period` (`period`);

--
-- AUTO_INCREMENT för dumpade tabeller
--

--
-- AUTO_INCREMENT för tabell `anvandare`
--
ALTER TABLE `anvandare`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT för tabell `exkluderadearbetsdagar`
--
ALTER TABLE `exkluderadearbetsdagar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT för tabell `placering`
--
ALTER TABLE `placering`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Restriktioner för dumpade tabeller
--

--
-- Restriktioner för tabell `anvandare`
--
ALTER TABLE `anvandare`
  ADD CONSTRAINT `ftg` FOREIGN KEY (`foretagid`) REFERENCES `arbetsplats` (`foretagsnamn`);

--
-- Restriktioner för tabell `narvarande`
--
ALTER TABLE `narvarande`
  ADD CONSTRAINT `pid` FOREIGN KEY (`pid`) REFERENCES `placering` (`id`);

--
-- Restriktioner för tabell `placering`
--
ALTER TABLE `placering`
  ADD CONSTRAINT `period` FOREIGN KEY (`period`) REFERENCES `period` (`periodnamn`),
  ADD CONSTRAINT `pnr` FOREIGN KEY (`personnummer`) REFERENCES `elev` (`pnr`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
