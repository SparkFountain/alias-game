-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 24. Mai 2020 um 22:15
-- Server-Version: 5.7.25
-- PHP-Version: 5.6.40

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `usr_web23388256_4`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `term`
--

CREATE TABLE `term` (
  `id` int(11) NOT NULL,
  `word` varchar(32) COLLATE latin1_german1_ci NOT NULL,
  `category` varchar(16) COLLATE latin1_german1_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

--
-- Daten für Tabelle `term`
--

INSERT INTO `term` (`id`, `word`, `category`) VALUES
(2, 'Homeoffice', 'corona'),
(3, 'Mundschutz', 'corona'),
(4, 'Toilettenpapier', 'corona'),
(5, 'Ausgangssperre', 'corona'),
(6, 'Pandemie', 'corona'),
(7, 'Sterblichkeit', 'corona'),
(8, 'Quarantäne', 'corona'),
(9, 'Schließung', 'corona'),
(10, 'Infektion', 'corona'),
(11, 'Impfstoff', 'corona'),
(12, 'Rettungspaket', 'corona'),
(13, 'Shutdown', 'corona'),
(14, 'Mindestabstand', 'corona'),
(15, 'Virus', 'corona'),
(16, 'Ansteckung', 'corona'),
(17, 'Krankheit', 'corona'),
(18, 'Husten', 'corona'),
(19, 'Atemnot', 'corona'),
(20, 'Reproduktionszahl', 'corona'),
(21, 'Arzt', 'corona'),
(22, 'Genesung', 'corona'),
(23, 'Ausbreitung', 'corona'),
(24, 'Herdenimmunität', 'corona'),
(25, 'Auto', 'mixed'),
(26, 'Hund', 'mixed'),
(27, 'Haus', 'mixed'),
(28, 'Garten', 'mixed'),
(29, 'Ziege', 'mixed'),
(30, 'Musik', 'mixed'),
(31, 'Restaurant', 'mixed'),
(32, 'Abend', 'mixed'),
(33, 'Computer', 'mixed'),
(34, 'Zahnarzt', 'mixed'),
(35, 'Mausefalle', 'mixed'),
(36, 'Bank', 'mixed'),
(37, 'Leder', 'mixed'),
(38, 'Weihachten', 'mixed'),
(39, 'Schwimmbad', 'mixed'),
(40, 'Altersheim', 'mixed'),
(41, 'Kerze', 'mixed'),
(42, 'Globus', 'mixed'),
(43, 'Zensur', 'mixed'),
(44, 'Nagel', 'mixed'),
(45, 'Hurrikan', 'mixed'),
(46, 'Draht', 'mixed'),
(47, 'Bosheit', 'mixed'),
(48, 'Luftschiff', 'mixed'),
(49, 'Brief', 'mixed'),
(50, 'Münze', 'mixed'),
(51, 'Einkaufswagen', 'mixed'),
(52, 'Erdbeben', 'mixed'),
(53, 'Million', 'mixed'),
(54, 'Kuss', 'mixed'),
(55, 'Schiedsrichter', 'mixed'),
(56, 'Kobra', 'mixed'),
(57, 'Szene', 'mixed'),
(58, 'Amulett', 'mixed'),
(59, 'Zustand', 'mixed'),
(60, 'Trennung', 'mixed'),
(61, 'Kilometer', 'mixed'),
(62, 'Schlange', 'mixed'),
(63, 'Kommunist', 'mixed'),
(64, 'Sweatshirt', 'mixed'),
(65, 'Kapsel', 'mixed'),
(66, 'Informant', 'mixed'),
(67, 'Krieg', 'mixed'),
(68, 'Seele', 'mixed'),
(69, 'Zeichnung', 'mixed'),
(70, 'Brunnen', 'mixed'),
(71, 'Schlacht', 'mixed'),
(72, 'Mutation', 'mixed'),
(73, 'Priester', 'mixed'),
(74, 'Beerdigung', 'mixed'),
(75, 'Schlag', 'mixed'),
(76, 'Schiene', 'mixed'),
(77, 'Stiefel', 'mixed'),
(78, 'Geheimnis', 'mixed'),
(79, 'Erosion', 'mixed'),
(80, 'Poster', 'mixed'),
(81, 'Böller', 'mixed'),
(82, 'Volleyball', 'mixed'),
(83, 'Geist', 'mixed'),
(84, 'Moskito', 'mixed'),
(85, 'Propaganda', 'mixed'),
(86, 'Motor', 'mixed'),
(87, 'Bandage', 'mixed'),
(88, 'Ferien', 'mixed'),
(89, 'Schrotflinte', 'mixed'),
(90, 'Bewegung', 'mixed'),
(91, 'Orient', 'mixed'),
(92, 'Etikett', 'mixed'),
(93, 'Kamm', 'mixed'),
(94, 'Sonntag', 'mixed'),
(95, 'Filter', 'mixed'),
(96, 'Radiergummi', 'mixed'),
(97, 'Windeln', 'mixed'),
(98, 'Donner', 'mixed'),
(99, 'Feier', 'mixed'),
(100, 'Offenbarung', 'mixed'),
(101, 'Kater', 'mixed'),
(102, 'Fisch', 'mixed'),
(103, 'Haltestelle', 'mixed'),
(104, 'Puzzle', 'mixed'),
(105, 'Fußball', 'mixed'),
(106, 'Freizeitpark', 'mixed'),
(107, 'Forum', 'mixed'),
(108, 'Fitness', 'mixed'),
(109, 'Küche', 'mixed'),
(110, 'Berg', 'mixed'),
(111, 'Ellbogen', 'mixed'),
(112, 'Antenne', 'mixed'),
(113, 'Adressat', 'mixed'),
(114, 'Zuneigung', 'mixed'),
(115, 'Kraft', 'mixed'),
(116, 'Sicherheit', 'mixed'),
(117, 'Trend', 'mixed'),
(118, 'Pistole', 'mixed'),
(119, 'Kaiser', 'mixed'),
(120, 'Schlaf', 'mixed'),
(121, 'Handy', 'mixed'),
(122, 'Kosten', 'mixed'),
(123, 'Taschengeld', 'mixed'),
(124, 'Tod', 'mixed'),
(125, 'Reifen', 'mixed'),
(126, 'Seilbahn', 'mixed'),
(127, 'Chemie', 'mixed'),
(128, 'Leinwand', 'mixed'),
(129, 'Satellit', 'mixed'),
(130, 'Version', 'mixed'),
(131, 'Beleidigung', 'mixed'),
(132, 'Bus', 'mixed'),
(133, 'Spielzeug', 'mixed'),
(134, 'Steigung', 'mixed'),
(135, 'Passagier', 'mixed'),
(136, 'Hauptgewinn', 'mixed'),
(137, 'Polizei', 'mixed'),
(138, 'Flur', 'mixed'),
(139, 'Regierung', 'mixed'),
(140, 'Suchmaschine', 'mixed'),
(141, 'Kantine', 'mixed'),
(142, 'Galgen', 'mixed'),
(143, 'Reihe', 'mixed'),
(144, 'Tonne', 'mixed'),
(145, 'Training', 'mixed'),
(146, 'Hochzeit', 'mixed'),
(147, 'Schmerz', 'mixed'),
(148, 'Ort', 'mixed'),
(149, 'Sorge', 'mixed'),
(150, 'Union', 'mixed'),
(151, 'Absolutismus', 'mixed'),
(152, 'Geschenk', 'mixed'),
(153, 'Signal', 'mixed'),
(154, 'Biologie', 'mixed'),
(155, 'Regisseur', 'mixed'),
(156, 'Staub', 'mixed'),
(157, 'CD', 'mixed'),
(158, 'Telefonbuch', 'mixed'),
(159, 'Gericht', 'mixed'),
(160, 'Laub', 'mixed'),
(161, 'Buchstabe', 'mixed'),
(162, 'Spielplatz', 'mixed'),
(163, 'Keks', 'mixed'),
(164, 'Ausgang', 'mixed'),
(165, 'Kurve', 'mixed'),
(166, 'Winterwald', 'mixed'),
(167, 'Sage', 'mixed'),
(168, 'Mauer', 'mixed'),
(169, 'Spaß', 'mixed'),
(170, 'Gewicht', 'mixed'),
(171, 'Religion', 'mixed'),
(172, 'Nadelbaum', 'mixed'),
(173, 'Blitz', 'mixed'),
(174, 'Mittagessen', 'mixed'),
(175, 'Beförderung', 'mixed'),
(176, 'Ausstieg', 'mixed'),
(177, 'Flüchtling', 'mixed'),
(178, 'Angst', 'mixed'),
(179, 'Zeitform', 'mixed'),
(180, 'Garage', 'mixed'),
(181, 'Koordinaten', 'mixed'),
(182, 'Poker', 'mixed'),
(183, 'Sohn', 'mixed'),
(184, 'Krimi', 'mixed'),
(185, 'Immobilie', 'mixed'),
(186, 'Sturz', 'mixed'),
(187, 'Zauberer', 'mixed'),
(188, 'Bonus', 'mixed'),
(189, 'Gegenwart', 'mixed'),
(190, 'Zukunft', 'mixed'),
(191, 'Sender', 'mixed'),
(192, 'Taktik', 'mixed'),
(193, 'Wäsche', 'mixed'),
(194, 'Lautsprecher', 'mixed'),
(195, 'Reisepass', 'mixed'),
(196, 'Urlaub', 'mixed'),
(197, 'Krankheit', 'mixed'),
(198, 'Postkarte', 'mixed'),
(199, 'Yak', 'mixed'),
(200, 'Wahrheit', 'mixed'),
(201, 'Steckdose', 'mixed'),
(202, 'Camping', 'mixed'),
(203, 'Bohne', 'mixed'),
(204, 'Tischler', 'mixed'),
(205, 'Krippe', 'mixed'),
(206, 'Entscheidung', 'mixed'),
(207, 'Lüge', 'mixed'),
(208, 'Tante', 'mixed'),
(209, 'Sehschwäche', 'mixed'),
(210, 'Fluss', 'mixed'),
(211, 'Stadt', 'mixed'),
(212, 'Land', 'mixed'),
(213, 'Tennis', 'mixed'),
(214, 'Müllabfuhr', 'mixed'),
(215, 'Beton', 'mixed'),
(216, 'Vertrauen', 'mixed'),
(217, 'Software', 'mixed'),
(218, 'Kopf', 'mixed'),
(219, 'Arbeiter', 'mixed'),
(220, 'Eis', 'mixed'),
(221, 'Segelschiff', 'mixed'),
(222, 'Beweis', 'mixed'),
(223, 'Absatz', 'mixed'),
(224, 'Zange', 'mixed'),
(225, 'Abenteuer', 'mixed'),
(226, 'Grenze', 'mixed'),
(227, 'Raum', 'mixed'),
(228, 'Volumen', 'mixed'),
(229, 'Handel', 'mixed'),
(230, 'Gebiet', 'mixed'),
(231, 'Blau', 'mixed'),
(232, 'Grün', 'mixed'),
(233, 'Gelb', 'mixed'),
(234, 'Rot', 'mixed'),
(235, 'Schwarz', 'mixed'),
(236, 'Weiß', 'mixed'),
(237, 'Himmel', 'mixed'),
(238, 'Erde', 'mixed'),
(239, 'Phantom', 'mixed'),
(240, 'Schalter', 'mixed'),
(241, 'Schaukel', 'mixed'),
(242, 'Wippe', 'mixed'),
(243, 'Kind', 'mixed'),
(244, 'Osten', 'mixed'),
(245, 'Süden', 'mixed'),
(246, 'Westen', 'mixed'),
(247, 'Norden', 'mixed'),
(248, 'Angel', 'mixed'),
(249, 'Hammer', 'mixed'),
(250, 'Reißleine', 'mixed'),
(251, 'Schornstein', 'mixed'),
(252, 'Vakuum', 'mixed'),
(253, 'Jetlag', 'mixed'),
(254, 'Richtung', 'mixed'),
(255, 'Kompass', 'mixed'),
(256, 'Oper', 'mixed'),
(257, 'Revolution', 'mixed'),
(258, 'Gasthaus', 'mixed'),
(259, 'Museum', 'mixed'),
(260, 'Ski', 'mixed'),
(261, 'Forscher', 'mixed'),
(262, 'Gravitation', 'mixed'),
(263, 'Kultur', 'mixed'),
(264, 'Roman', 'mixed'),
(265, 'Auferstehung', 'mixed'),
(266, 'Alkohol', 'mixed'),
(267, 'Tabak', 'mixed'),
(268, 'Galaxie', 'mixed'),
(269, 'Ereignis', 'mixed'),
(270, 'Hochwasser', 'mixed'),
(271, 'Lagerfeuer', 'mixed'),
(272, 'Moderation', 'mixed'),
(273, 'Fernseher', 'mixed'),
(274, 'Radio', 'mixed'),
(275, 'Internet', 'mixed'),
(276, 'Kommunikation', 'mixed'),
(277, 'Herde', 'mixed'),
(278, 'Kontrolle', 'mixed'),
(279, 'Feenstaub', 'mixed'),
(280, 'Chronik', 'mixed'),
(281, 'Hochschule', 'mixed'),
(282, 'Zoo', 'mixed'),
(283, 'Yachthafen', 'mixed'),
(284, 'Diskothek', 'mixed'),
(285, 'Wahnsinn', 'mixed'),
(286, 'Sonnenbrille', 'mixed'),
(287, 'Koralle', 'mixed'),
(288, 'Voodoo', 'mixed'),
(289, 'Mission', 'mixed'),
(290, 'Grad', 'mixed'),
(291, 'Philosophie', 'mixed'),
(292, 'Geografie', 'mixed'),
(293, 'Liebesbrief', 'mixed'),
(294, 'Seifenspender', 'mixed'),
(295, 'Kaktus', 'mixed'),
(296, 'Hymne', 'mixed'),
(297, 'Landkarte', 'mixed'),
(298, 'Gegenstand', 'mixed'),
(299, 'Experiment', 'mixed'),
(300, 'Antrieb', 'mixed'),
(301, 'Saison', 'mixed'),
(302, 'Flagge', 'mixed'),
(303, 'Eishockey', 'mixed'),
(304, 'Basketball', 'mixed'),
(305, 'Jahrhundert', 'mixed'),
(306, 'Iglu', 'mixed'),
(307, 'Lachkrampf', 'mixed'),
(308, 'Kirche', 'mixed'),
(309, 'Sandstrand', 'mixed'),
(310, 'Teufel', 'mixed'),
(311, 'Waschpulver', 'mixed'),
(312, 'Behinderung', 'mixed'),
(313, 'Malerei', 'mixed'),
(314, 'Osterei', 'mixed'),
(315, 'Mannschaft', 'mixed'),
(316, 'Belichtung', 'mixed'),
(317, 'Diebstahl', 'mixed'),
(318, 'Auflauf', 'mixed'),
(319, 'Taschentuch', 'mixed'),
(320, 'Schreibblock', 'mixed'),
(321, 'Müdigkeit', 'mixed'),
(322, 'Fragezeichen', 'mixed'),
(323, 'Nachfolger', 'mixed'),
(324, 'Schokolade', 'mixed'),
(325, 'Äquator', 'mixed'),
(326, 'Wunsch', 'mixed'),
(327, 'Fantasie', 'mixed'),
(328, 'Winkel', 'mixed'),
(329, 'Schicksal', 'mixed'),
(330, 'Holz', 'mixed'),
(331, 'Gründung', 'mixed'),
(332, 'Ausrede', 'mixed'),
(333, 'Vortrag', 'mixed'),
(334, 'Abgrund', 'mixed'),
(335, 'Blumenstrauß', 'mixed'),
(336, 'Flitterwochen', 'mixed'),
(337, 'Mitglied', 'mixed'),
(338, 'Abkürzung', 'mixed'),
(339, 'Kreuz', 'mixed'),
(340, 'Spaten', 'mixed'),
(341, 'Ebbe', 'mixed'),
(342, 'Flut', 'mixed'),
(343, 'Zartbitter', 'mixed'),
(344, 'Erker', 'mixed'),
(345, 'Teenager', 'mixed'),
(346, 'Picknick', 'mixed'),
(347, 'Frühling', 'mixed'),
(348, 'Sommer', 'mixed'),
(349, 'Herbst', 'mixed'),
(350, 'Winter', 'mixed'),
(351, 'Wellness', 'mixed'),
(352, 'Mosaik', 'mixed'),
(353, 'Xylophon', 'mixed'),
(354, 'Unterschied', 'mixed'),
(355, 'Inspiration', 'mixed'),
(356, 'Anlage', 'mixed'),
(357, 'Weinverkostung', 'mixed'),
(358, 'Willkür', 'mixed'),
(359, 'Rache', 'mixed'),
(360, 'Abneigung', 'mixed'),
(361, 'Notizbuch', 'mixed'),
(362, 'Kinofilm', 'mixed'),
(363, 'Wortschatz', 'mixed'),
(364, 'Zitat', 'mixed'),
(365, 'Lesezeichen', 'mixed'),
(366, 'Artikel', 'mixed'),
(367, 'Zucker', 'mixed'),
(368, 'Wackelpudding', 'mixed'),
(369, 'Eisenbahn', 'mixed'),
(370, 'Unkraut', 'mixed'),
(371, 'Rasenmäher', 'mixed'),
(372, 'Surfbrett', 'mixed'),
(373, 'Zeugnis', 'mixed'),
(374, 'Kinderbett', 'mixed'),
(375, 'Vorteil', 'mixed'),
(376, 'Kapitän', 'mixed'),
(377, 'Therapie', 'mixed'),
(378, 'Diät', 'mixed'),
(379, 'Erziehung', 'mixed'),
(380, 'Friseur', 'mixed'),
(381, 'Dachdecker', 'mixed'),
(382, 'Tourismus', 'mixed'),
(383, 'Landschaft', 'mixed'),
(384, 'Mathematik', 'mixed'),
(385, 'Steinkohle', 'mixed'),
(386, 'Apotheke', 'mixed'),
(387, 'Gips', 'mixed'),
(388, 'Toilette', 'mixed'),
(389, 'Botschaft', 'mixed'),
(390, 'Bibel', 'mixed'),
(391, 'Antwort', 'mixed'),
(392, 'Muskatnuss', 'mixed'),
(393, 'Muskeln', 'mixed'),
(394, 'Wahrzeichen', 'mixed'),
(395, 'Traumschiff', 'mixed'),
(396, 'Paradies', 'mixed'),
(397, 'Uhrzeit', 'mixed'),
(398, 'Schulden', 'mixed'),
(399, 'Bahnhof', 'mixed'),
(400, 'Flughafen', 'mixed'),
(401, 'Tütensuppe', 'mixed'),
(402, 'Wärmflasche', 'mixed'),
(403, 'Heizung', 'mixed'),
(404, 'Stromkasten', 'mixed'),
(405, 'Wattenmeer', 'mixed'),
(406, 'Bambus', 'mixed'),
(407, 'Angebot', 'mixed'),
(408, 'Kalender', 'mixed'),
(409, 'Geschichte', 'mixed'),
(410, 'Spezialität', 'mixed'),
(411, 'Palme', 'mixed'),
(412, 'Mikrowelle', 'mixed'),
(413, 'Besenstiel', 'mixed'),
(414, 'Kochtopf', 'mixed'),
(415, 'Bratpfanne', 'mixed'),
(416, 'Pfannkuchen', 'mixed'),
(417, 'Markierung', 'mixed'),
(418, 'Triangel', 'mixed'),
(419, 'Tambourin', 'mixed'),
(420, 'Absperrung', 'mixed'),
(421, 'Speisekarte', 'mixed'),
(422, 'Melone', 'mixed'),
(423, 'Sonnenschirm', 'mixed'),
(424, 'Papagei', 'mixed'),
(425, 'Intonation', 'jazzchor'),
(426, 'Rhythmus', 'jazzchor'),
(427, 'Groove', 'jazzchor'),
(428, 'Gesang', 'jazzchor'),
(429, 'A-Cappella', 'jazzchor'),
(430, 'Nasal', 'jazzchor'),
(431, 'Popmusik', 'jazzchor'),
(432, 'Jazz', 'jazzchor'),
(433, 'Mehrstimmig', 'jazzchor'),
(434, 'Performance', 'jazzchor'),
(435, 'Bühnenwirkung', 'jazzchor'),
(436, 'Ausstrahlung', 'jazzchor'),
(437, 'Outfit', 'jazzchor'),
(438, 'Rampenlicht', 'jazzchor'),
(439, 'Aquarium', 'jazzchor'),
(440, 'Sopran', 'jazzchor'),
(441, 'Alt', 'jazzchor'),
(442, 'Tenor', 'jazzchor'),
(443, 'Bass', 'jazzchor'),
(444, 'Solist', 'jazzchor'),
(445, 'Harmonie', 'jazzchor'),
(446, 'Akkord', 'jazzchor'),
(447, 'Melodie', 'jazzchor'),
(448, 'Chorleiter', 'jazzchor'),
(449, 'Repertoire', 'jazzchor'),
(450, 'Akustik', 'jazzchor'),
(451, 'Wettbewerb', 'jazzchor'),
(452, 'Kultur', 'jazzchor'),
(453, 'Konzert', 'jazzchor'),
(454, 'Applaus', 'jazzchor'),
(455, 'Generalprobe', 'jazzchor'),
(456, 'Stimme', 'jazzchor'),
(457, 'Note', 'jazzchor'),
(458, 'Lerndatei', 'jazzchor'),
(459, 'Chorfahrt', 'jazzchor'),
(460, 'Loch', 'over-18'),
(461, 'Tanga', 'over-18'),
(462, 'Hoden', 'over-18'),
(463, 'Ejakulation', 'over-18'),
(464, 'Abstinenz', 'over-18'),
(465, 'Abtreibung', 'over-18'),
(466, 'Affäre', 'over-18'),
(467, 'After', 'over-18'),
(468, 'AIDS', 'over-18'),
(469, 'Analverkehr', 'over-18'),
(470, 'Fellatio', 'over-18'),
(471, 'Aufklärung', 'over-18'),
(472, 'Ausfluss', 'over-18'),
(473, 'Befriedigung', 'over-18'),
(474, 'Beischlaf', 'over-18'),
(475, 'BH', 'over-18'),
(476, 'Bisexuell', 'over-18'),
(477, 'Blowjob', 'over-18'),
(478, 'Bordell', 'over-18'),
(479, 'Brustwarze', 'over-18'),
(480, 'Busen', 'over-18'),
(481, 'Callboy', 'over-18'),
(482, 'Chlamydien', 'over-18'),
(483, 'Cockring', 'over-18'),
(484, 'Coming-Out', 'over-18'),
(485, 'Cunnilingus', 'over-18'),
(486, 'Cybersex', 'over-18'),
(487, 'Diaphragma', 'over-18'),
(488, 'Dildo', 'over-18'),
(489, 'Doktorspiele', 'over-18'),
(490, 'Domina', 'over-18'),
(491, 'Dreier', 'over-18'),
(492, 'Eichel', 'over-18'),
(493, 'Eisprung', 'over-18'),
(494, 'Ekstase', 'over-18'),
(495, 'Empfängnis', 'over-18'),
(496, 'Entjungferung', 'over-18'),
(497, 'Erektion', 'over-18'),
(498, 'Erregung', 'over-18'),
(499, 'Eunuch', 'over-18'),
(500, 'Exhibitionist', 'over-18'),
(501, 'Fellatio', 'over-18'),
(502, 'Fetisch', 'over-18'),
(503, 'Flirt', 'over-18'),
(504, 'Fötus', 'over-18'),
(505, 'Freier', 'over-18'),
(506, 'Fremdgehen', 'over-18'),
(507, 'Fruchtwasser', 'over-18'),
(508, 'Fummeln', 'over-18'),
(509, 'G-Punkt', 'over-18'),
(510, 'Gangbang', 'over-18'),
(511, 'Gebärmutter', 'over-18'),
(512, 'Geil', 'over-18'),
(513, 'Genitalien', 'over-18'),
(514, 'Gonorrhö', 'over-18'),
(515, 'Gruppensex', 'over-18'),
(516, 'Hepatitis', 'over-18'),
(517, 'HIV', 'over-18'),
(518, 'Hormone', 'over-18'),
(519, 'Impotenz', 'over-18'),
(520, 'Infertilität', 'over-18'),
(521, 'Intimität', 'over-18'),
(522, 'Inzest', 'over-18'),
(523, 'Jungfernhäutchen', 'over-18'),
(524, 'Kamasutra', 'over-18'),
(525, 'Kastration', 'over-18'),
(526, 'Klitoris', 'over-18'),
(527, 'Kondom', 'over-18'),
(528, 'Kuss', 'over-18'),
(529, 'Leihmutter', 'over-18'),
(530, 'Libido', 'over-18'),
(531, 'Lovetoys', 'over-18'),
(532, 'Lusttropfen', 'over-18'),
(533, 'Masturbation', 'over-18'),
(534, 'Menopause', 'over-18'),
(535, 'Menstruation', 'over-18'),
(536, 'Monogamie', 'over-18'),
(537, 'Muttermund', 'over-18'),
(538, 'Nachspiel', 'over-18'),
(539, 'Obszön', 'over-18'),
(540, 'One-Night-Stand', 'over-18'),
(541, 'Orgie', 'over-18'),
(542, 'Periode', 'over-18'),
(543, 'Petting', 'over-18'),
(544, 'Pille', 'over-18'),
(545, 'Porno', 'over-18'),
(546, 'Pubertät', 'over-18'),
(547, 'Queer', 'over-18'),
(548, 'Quickie', 'over-18'),
(549, 'Rendezvous', 'over-18'),
(550, 'Rotlichtviertel', 'over-18'),
(551, 'Schamhaare', 'over-18'),
(552, 'Schwangerschaft', 'over-18'),
(553, 'Schwul', 'over-18'),
(554, 'Sexappeal', 'over-18'),
(555, 'Swingerclub', 'over-18'),
(556, 'Telefonsex', 'over-18'),
(557, 'Urologe', 'over-18'),
(558, 'Venushügel', 'over-18'),
(559, 'Viagra', 'over-18'),
(560, 'Vorhaut', 'over-18'),
(561, 'Voyeur', 'over-18'),
(562, 'Wechseljahre', 'over-18'),
(563, 'Seitensprung', 'over-18'),
(564, 'Cocktail', 'party'),
(565, 'Palmen', 'party'),
(566, 'Bikini', 'party'),
(567, 'Pool', 'party'),
(568, 'Disco', 'party'),
(569, 'Bier', 'party'),
(570, 'Spaß', 'party');

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `term`
--
ALTER TABLE `term`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `term`
--
ALTER TABLE `term`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=571;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
