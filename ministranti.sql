-- phpMyAdmin SQL Dump
-- version 3.4.9
-- http://www.phpmyadmin.net
--
-- Počítač: localhost
-- Vygenerováno: Stř 03. pro 2014, 15:34
-- Verze MySQL: 5.5.20
-- Verze PHP: 5.3.9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Databáze: `ministranti`
--

-- --------------------------------------------------------

--
-- Struktura tabulky `full_or_extra`
--

CREATE TABLE IF NOT EXISTS `full_or_extra` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `extra` tinyint(1) NOT NULL,
  `datum` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabulky `levels`
--

CREATE TABLE IF NOT EXISTS `levels` (
  `id_lvl` int(11) NOT NULL AUTO_INCREMENT,
  `obrazok` varchar(255) COLLATE utf8_slovak_ci NOT NULL,
  `obrazok_mini` varchar(255) COLLATE utf8_slovak_ci NOT NULL,
  `body` int(11) NOT NULL,
  `nazov` varchar(255) COLLATE utf8_slovak_ci NOT NULL,
  PRIMARY KEY (`id_lvl`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci AUTO_INCREMENT=5 ;

--
-- Vypisuji data pro tabulku `levels`
--

INSERT INTO `levels` (`id_lvl`, `obrazok`, `obrazok_mini`, `body`, `nazov`) VALUES
(3, 'Pastier_svin.png', 'Pastier_svin_mini.png', 0, 'Pastier svíň'),
(4, 'Rozsievac.png', 'Rozsievac_mini.png', 30, 'Rozsievač');

-- --------------------------------------------------------

--
-- Struktura tabulky `oznamy`
--

CREATE TABLE IF NOT EXISTS `oznamy` (
  `id_oznam` int(11) NOT NULL AUTO_INCREMENT,
  `nadpis` varchar(255) COLLATE utf8_slovak_ci NOT NULL,
  `text` tinytext COLLATE utf8_slovak_ci NOT NULL,
  `datum` date NOT NULL,
  `active` tinyint(1) NOT NULL,
  PRIMARY KEY (`id_oznam`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci AUTO_INCREMENT=6 ;

--
-- Vypisuji data pro tabulku `oznamy`
--

INSERT INTO `oznamy` (`id_oznam`, `nadpis`, `text`, `datum`, `active`) VALUES
(3, 'Začiatok miništrantskej súťaže', 'Dňa 30.11.2014 sa spustí strínka miništrantskej súťaže www.ministranti.besaba.com a začne sa miništrantská súťaž.', '2014-11-19', 1),
(5, 'oznam 1', 'niečo niečo niečo ... ..', '2014-11-09', 0);

-- --------------------------------------------------------

--
-- Struktura tabulky `pribehy`
--

CREATE TABLE IF NOT EXISTS `pribehy` (
  `id_pribeh` int(11) NOT NULL AUTO_INCREMENT,
  `nazov` varchar(255) COLLATE utf8_slovak_ci NOT NULL,
  `text` text COLLATE utf8_slovak_ci NOT NULL,
  `datum` date NOT NULL,
  PRIMARY KEY (`id_pribeh`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci AUTO_INCREMENT=8 ;

--
-- Vypisuji data pro tabulku `pribehy`
--

INSERT INTO `pribehy` (`id_pribeh`, `nazov`, `text`, `datum`) VALUES
(7, 'kde bolo tam bolo', 'kde bolo tam bolo bol raz jeden pribeh', '2014-11-18');

-- --------------------------------------------------------

--
-- Struktura tabulky `sluzby`
--

CREATE TABLE IF NOT EXISTS `sluzby` (
  `id_sluzba` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `id_termin` int(11) NOT NULL,
  PRIMARY KEY (`id_sluzba`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabulky `termins`
--

CREATE TABLE IF NOT EXISTS `termins` (
  `id_termin` int(11) NOT NULL AUTO_INCREMENT,
  `datum` varchar(255) COLLATE utf8_slovak_ci NOT NULL,
  `cas` varchar(255) COLLATE utf8_slovak_ci NOT NULL,
  `max_miest` int(11) NOT NULL,
  `volne_miesta` int(11) NOT NULL,
  `poznamka` text COLLATE utf8_slovak_ci NOT NULL,
  PRIMARY KEY (`id_termin`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabulky `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `meno` varchar(255) COLLATE utf8_slovak_ci NOT NULL,
  `krstne` varchar(255) COLLATE utf8_slovak_ci NOT NULL,
  `priezvisko` varchar(255) COLLATE utf8_slovak_ci NOT NULL,
  `heslo` varchar(255) COLLATE utf8_slovak_ci NOT NULL,
  `profilovka` varchar(255) COLLATE utf8_slovak_ci NOT NULL,
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `body` int(11) NOT NULL DEFAULT '0',
  `body_minis` int(11) NOT NULL DEFAULT '0',
  `body_bonus` int(11) NOT NULL DEFAULT '0',
  `admin` varchar(7) COLLATE utf8_slovak_ci NOT NULL,
  `visible` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci AUTO_INCREMENT=6 ;

--
-- Vypisuji data pro tabulku `users`
--

INSERT INTO `users` (`meno`, `krstne`, `priezvisko`, `heslo`, `profilovka`, `id_user`, `body`, `body_minis`, `body_bonus`, `admin`, `visible`) VALUES
('xtom93x', 'Tomáš', 'Žitňanský', 'c61c07388fe2a6d299140cad1e592d07', 'obr/profilovky/profil01.jpg', 1, 0, 0, 0, '1111111', 0),
('minis', 'Minštrant', 'Prvý', 'caf1a3dfb505ffed0d024130f58c5cfa', '0', 3, 45, 0, 0, '0', 1),
('root', 'Robin', 'Hood', '75baeedf1d73b68b289decfa8a3dd192', '', 5, 0, 0, 0, '1111111', 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
