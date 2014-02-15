INSERT INTO `locations` (`idlocations`, `location`) VALUES
(1, 'Flensburg'),
(2, 'Heide'),
(3, 'Kiel'),
(4, 'Lübeck'),
(5, 'Osterrönfeld'),
(6, 'Wedel');

INSERT INTO `mensen` (`idmensen`, `name`, `locations_idlocations`) VALUES
(1, 'Mensa', 1),
(2, 'Cafeteria Munketoft', 1),
(3, 'Mensa FHW Heide', 2),
(4, 'Mensa 1 am Westring', 3),
(5, 'Mensa 2', 3),
(6, 'Bresterie in der Mensa 2', 3),
(7, 'Mensa Kesselhaus', 3),
(8, 'Mensa-Gaarden', 3),
(9, 'Schwentine-Mensa', 3),
(10, 'Mensa', 4),
(11, 'Mensa Osterrönfeld', 5),
(12, 'Mensa und Cafetria Wedel', 6);