drop table if exists categoria cascade;
drop table if exists categoria_simples cascade;
drop table if exists super_categoria cascade;
drop table if exists constituida cascade;
drop table if exists produto cascade;
drop table if exists fornecedor cascade;
drop table if exists fornece_sec cascade;
drop table if exists corredor cascade;
drop table if exists prateleira cascade;
drop table if exists planograma cascade;
drop table if exists evento_reposicao cascade;
drop table if exists reposicao cascade;
drop function if exists check_cat_proc();
drop trigger if exists check_cat on categoria;
drop function if exists check_cat_simples_in_super(varchar);
drop function if exists check_cat_super_in_simples(varchar);
drop function if exists check_super_in_const_proc();
drop trigger if exists check_super_in_const on super_categoria;
drop function if exists check_sec_in_primario(varchar, varchar);
drop trigger if exists check_ean_in_fornece_sec on produto;
drop function if exists check_ean_in_fornece_sec_proc();
drop trigger if exists check_cat_cycle on consituida;
drop function if exists check_cat_cycle_proc();



create table categoria (
    nome    varchar(80) not null    unique,
    primary key(nome)
    
);

create table categoria_simples (
    nome    varchar(80) not null    unique, 
    primary key(nome),
    foreign key(nome)
        references categoria(nome)
);

create table super_categoria (
    nome    varchar(80) not null    unique,
    primary key(nome),
    foreign key(nome)
        references categoria(nome) 
 
    
);

create table constituida (
    super_categoria varchar(80) not null,
    categoria       varchar(80) not null,
    primary key(super_categoria, categoria),
    foreign key(super_categoria)
        references super_categoria(nome),
    foreign key(categoria)
        references categoria(nome),
    check(super_categoria <> categoria)
   
);

create table fornecedor (
    nif     char(9)  not null   unique,
    nome    varchar(80) not null,
    primary key(nif)
);

create table produto (
    ean             char(13)  not null  unique,
    design          varchar(80) not null,
    categoria       varchar(80) not null,
    forn_primario   char(9)  not null,
    data_op         date        not null,
    primary key(ean),
    foreign key(categoria)
        references categoria(nome),
    foreign key(forn_primario)
        references fornecedor(nif)
);

create table fornece_sec (
    nif     char(9)  not null,
    ean     char(13)  not null,
    primary key(nif, ean),
    foreign key(nif)
        references fornecedor(nif),
    foreign key(ean)
        references produto(ean)

);

create table corredor (
    nro     numeric(5)  not null    unique,
    largura numeric(4,2)  not null,
    primary key(nro)
);

create table prateleira (
    nro     numeric(5)  not null,
    lado    varchar(8) not null,
    altura  varchar(8) not null,
    primary key(nro, lado, altura),
    foreign key(nro)
        references corredor(nro)
);

create table planograma(
    ean     char(13)  not null,
    nro     numeric(5)  not null,
    lado    varchar(8) not null,
    altura  varchar(8) not null,
    face    numeric(10) not null,
    unidades numeric(10) not null,
    loc     numeric(10) not null,
    primary key(ean, nro, lado, altura),
    foreign key(ean)
        references produto(ean),
    foreign key(nro, lado, altura)
        references prateleira(nro, lado, altura)
);

create table evento_reposicao(
    operador    varchar(80) not null,
    instante    TIMESTAMP     not null,
    primary key(operador, instante)
);

create table reposicao(
    ean     char(13)    not null,
    nro     numeric(13) not null,
    lado    varchar(8)  not null,
    altura  varchar(8)  not null,
    operador varchar(80) not null,
    instante TIMESTAMP  not null,
    unidades numeric(10) not null,
    primary key(ean, nro, lado, altura, operador, instante),
    foreign key(ean, nro, lado, altura)
        references planograma(ean, nro, lado, altura),
    foreign key(operador, instante)
        references evento_reposicao(operador, instante),
    check(instante < CURRENT_TIMESTAMP)
);


/***********
TRIGGERS AND FUNCTIONS
***********/

-- RI-RE1 Obriga a que a categoria seja super categoria ou categoria simples

CREATE FUNCTION check_cat_proc()
    RETURNS TRIGGER AS $body$
    BEGIN
        IF NEW.nome NOT IN (SELECT nome FROM super_categoria UNION SELECT nome FROM categoria_simples) THEN
            RAISE EXCEPTION 'Categoria tem de estar em super_categoria ou categoria_simples';
        END IF;
        RETURN NEW;
    END;
$body$ LANGUAGE plpgsql;

CREATE CONSTRAINT TRIGGER check_cat AFTER INSERT ON categoria
DEFERRABLE INITIALLY DEFERRED
FOR EACH ROW EXECUTE PROCEDURE check_cat_proc();



-- RI-RE2_1 Verifica se nao existe nenhuma super categoria presente em categoria simples

CREATE FUNCTION check_cat_simples_in_super(simples varchar)
    RETURNS boolean AS $body$
    BEGIN
        RETURN (simples NOT IN (SELECT nome from super_categoria));
    END;
$body$ LANGUAGE plpgsql;

ALTER TABLE categoria_simples
ADD CHECK(check_cat_simples_in_super(nome));



-- RI-RE 2_2 Verifica se nao existe nenhuma categoria simples presente em super categoria

CREATE FUNCTION check_cat_super_in_simples(super varchar)
    RETURNS boolean AS $body$
    BEGIN
        RETURN (super NOT IN (SELECT nome from categoria_simples));
    END;
$body$ LANGUAGE plpgsql;

ALTER TABLE super_categoria
ADD CHECK(check_cat_super_in_simples(nome));



-- RI-RE3 obriga a que a super categoria esteja na relacao constituida

CREATE FUNCTION check_super_in_const_proc()
    RETURNS TRIGGER AS $body$
    BEGIN
        IF NEW.nome NOT IN (SELECT super_categoria FROM constituida) THEN
            RAISE EXCEPTION 'Super Categoria nao esta na relacao constituida';
        END IF;
        RETURN NEW;
    END;
$body$ LANGUAGE plpgsql;

CREATE CONSTRAINT TRIGGER check_super_in_const AFTER INSERT ON super_categoria
DEFERRABLE INITIALLY DEFERRED
FOR EACH ROW EXECUTE PROCEDURE check_super_in_const_proc();



-- RI-EA1 nao pode haver ciclos super_categoria e categoria

/*haver ciclo significa que a super categoria é uma
das sub-categorias da categoria (em toda a profundidade)*/
CREATE FUNCTION check_cat_cycle_proc()
    RETURNS TRIGGER AS $body$
    BEGIN
        IF (NEW.super_categoria IN (with recursive tree as(  --query que verifica a condicao dos ciclos--

						select super_categoria, categoria --sub-categorias diretas da categoria a ser inserida--
						from constituida
						where super_categoria = NEW.categoria
						union all
						select child.super_categoria, child.categoria  
						from constituida as child
						join tree as parent on parent.categoria = child.super_categoria  --sub-categorias de cada sub-categoria--
						)
						select categoria
						from tree)) THEN
            RAISE EXCEPTION 'Nao pode haver ciclos super categoria e categoria';
        END IF;
        RETURN NEW;

    END;
$body$ LANGUAGE plpgsql;

CREATE TRIGGER check_cat_cycle BEFORE INSERT ON constituida
FOR EACH ROW EXECUTE PROCEDURE check_cat_cycle_proc();



-- RI-RE3 (mesmo nome mas ri diferente) obriga a que o ean exista na relacao fornece_sec

CREATE FUNCTION check_ean_in_fornece_sec_proc()
    RETURNS TRIGGER AS $body$
    BEGIN
        IF NEW.ean NOT IN (SELECT ean FROM fornece_sec) THEN
            RAISE EXCEPTION 'Produto nao esta na relacao fornece_sec';
        END IF;
        RETURN NEW;
    END;
$body$ LANGUAGE plpgsql;

CREATE CONSTRAINT TRIGGER check_ean_in_fornece_sec AFTER INSERT ON produto
DEFERRABLE INITIALLY DEFERRED
FOR EACH ROW EXECUTE PROCEDURE check_ean_in_fornece_sec_proc();


-- RI-EA4 o fornecedor primario de um produto nao pode existir na relacao fornece_sec para o mesmo produto

CREATE FUNCTION check_sec_in_primario(nif varchar, product_ean varchar)
    RETURNS boolean AS $body$
    BEGIN
        RETURN (nif NOT IN (SELECT forn_primario FROM produto p WHERE p.ean = product_ean ));
    END;
$body$ LANGUAGE plpgsql;

ALTER TABLE fornece_sec
ADD CHECK(check_sec_in_primario(nif,ean));
