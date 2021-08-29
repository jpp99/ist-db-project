START TRANSACTION;
insert into categoria values ('bebidas');
insert into categoria values ('refrigerantes');
insert into categoria values ('batidos');
insert into categoria values ('vinhos');
insert into categoria values ('livros');
insert into categoria values ('comida');
insert into categoria values ('carne');
insert into categoria values ('peixe');
insert into categoria values ('peixe do mar');
insert into categoria values ('peixe do rio');
insert into categoria values ('frango');



insert into super_categoria values('bebidas');
insert into super_categoria values('comida');
insert into super_categoria values('peixe');
insert into super_categoria values('carne');



insert into categoria_simples values ('refrigerantes');
insert into categoria_simples values ('batidos');
insert into categoria_simples values ('vinhos');
insert into categoria_simples values ('livros');
insert into categoria_simples values ('frango');
insert into categoria_simples values ('peixe do mar');
insert into categoria_simples values ('peixe do rio');

insert into constituida values ('bebidas','refrigerantes');
insert into constituida values ('bebidas','batidos');
insert into constituida values ('bebidas','vinhos');
insert into constituida values ('comida','carne');
insert into constituida values ('comida','peixe');
insert into constituida values ('peixe','peixe do mar');
insert into constituida values ('peixe','peixe do rio');
insert into constituida values ('carne','frango');

COMMIT;

insert into fornecedor values ('123456789','Maria');
insert into fornecedor values ('987654321','Jota');
insert into fornecedor values ('135791357','Antonio');
insert into fornecedor values ('024680246','Marta');
insert into fornecedor values ('036925825','Afonso');
insert into fornecedor values ('000000001','Ana');
insert into fornecedor values ('000000002','Beatriz');

START TRANSACTION;

insert into produto values ('0000000000001','coca-cola','refrigerantes','123456789','2016-07-11');
insert into produto values ('0000000000002','fanta','refrigerantes','987654321','2017-01-01');
insert into produto values ('0000000000003','sumol','refrigerantes','123456789','2019-01-02');
insert into produto values ('0000000000004','3 amigos','vinhos','135791357','2017-05-06');
insert into produto values ('0000000000005','fonte de pias','vinhos','135791357','2016-07-11');
insert into produto values ('0000000000006','coxa de vitela','carne','987654321','2018-11-11');
insert into produto values ('0000000000007','robalo','peixe do mar','000000001','2017-02-21');
insert into produto values ('0000000000008','salmao','peixe do rio','036925825','2018-05-07');
insert into produto values ('0000000000009','asas de frango','carne','987654321','2019-03-08');

insert into fornece_sec values('000000002','0000000000007');
insert into fornece_sec values('000000002','0000000000004');
insert into fornece_sec values('123456789','0000000000004');
insert into fornece_sec values('000000002','0000000000001');
insert into fornece_sec values('123456789','0000000000002');
insert into fornece_sec values('000000002','0000000000003');
insert into fornece_sec values('123456789','0000000000005');
insert into fornece_sec values('123456789','0000000000006');
insert into fornece_sec values('123456789','0000000000008');
insert into fornece_sec values('123456789','0000000000009');

COMMIT;

insert into corredor values('1','10');
insert into corredor values('2','20');
insert into corredor values('3','30');

insert into prateleira values(1,'esquerdo','chão');
insert into prateleira values(1,'esquerdo','médio');
insert into prateleira values(1,'esquerdo','superior');
insert into prateleira values(1,'direito','médio');
insert into prateleira values(1,'direito','superior');
insert into prateleira values(2,'direito','médio');
insert into prateleira values(2,'esquerdo','chão');
insert into prateleira values(3,'esquerdo','médio');
insert into prateleira values(3,'direito','médio');

insert into planograma values('0000000000001',1,'esquerdo','chão',5,2,8);
insert into planograma values('0000000000002',1,'esquerdo','médio',3,6,1);
insert into planograma values('0000000000003',1,'esquerdo','superior',5,2,5);
insert into planograma values('0000000000004',2,'direito','médio',2,6,3);
insert into planograma values('0000000000005',1,'direito','médio',6,3,8);
insert into planograma values('0000000000006',1,'direito','superior',9,5,7);
insert into planograma values('0000000000007',2,'esquerdo','chão',4,19,8);
insert into planograma values('0000000000008',3,'direito','médio',5,2,5);
insert into planograma values('0000000000009',3,'esquerdo','médio',5,2,5);

insert into evento_reposicao values('Antonio','2019-02-01 22:10:10');
insert into evento_reposicao values('Antonio','2019-02-02 12:30:10');
insert into evento_reposicao values('Antonio','2019-02-05 22:10:10');
insert into evento_reposicao values('Joao','2019-02-01 22:10:10');

insert into reposicao values('0000000000001',1,'esquerdo','chão','Antonio','2019-02-01 22:10:10', 10);
insert into reposicao values('0000000000001',1,'esquerdo','chão','Joao','2019-02-01 22:10:10', 8);
insert into reposicao values('0000000000002',1,'esquerdo','médio','Antonio','2019-02-02 12:30:10',9);
insert into reposicao values('0000000000002',1,'esquerdo','médio','Antonio','2019-02-05 22:10:10',25);











