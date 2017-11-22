create schema meta;

create table meta.acd (acd smallint)
insert into meta.acd (acd) values(1),(9)


create table meta.split (split smallint)
insert into meta.split (split) values
(507),
(52),
(505),
(50),
(53),
(506)

create table meta.intvl (intvl smallint)
insert into meta.intvl values (15)

create table meta.extension(extension CHAR(15))
insert into meta.extension values
('2601'),
('2618'),
('2624'),
('2625'),
('2614'),
('2699'),
('2698'),
('2615'),
('2611'),
('2600'),
('2617'),
('2612'),
('2616')

create table meta.logid(logid char(15))
insert into meta.logid values
('1615'),
('1612'),
('1799'),
('1617'),
('1611'),
('1614'),
('1700'),
('1701'),
('1616'),
('1618')

create table meta.vdn (vdn char(15))
insert into meta.vdn values
('1205'),
('1212'),
('1217'),
('1860'),
('2505'),
('2506'),
('2507'),
('2511'),
('2512'),
('2517'),
('2690')

create table meta.vector (vector smallint)
truncate table meta.vector
insert into meta.vector values
(1),
(53),
(1860),
(2517),
(5050),
(5058),
(5111),
(5121),
(5171),
(5172),
(5173),
(6901)