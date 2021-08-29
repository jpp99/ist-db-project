/**** QUERY1 ****/
with lista_categorias as(
select distinct forn_primario as nif, categoria     --categorias de cada fornecedor primario--
from produto
UNION
select distinct nif, categoria     --categorias de cada fornecedor secundario--
from fornece_sec
natural join produto
),
/*nif e count do maior fornecedor*/
conta_lista as(
    select nif, count(*)   
    from lista_categorias
    group by nif
    having count(*) >= all(
        select count(*)
        from lista_categorias
        group by nif
    )
)
/*nome do maior fornecedor*/
select nome
from conta_lista
natural join fornecedor;



/**** QUERY 2 ****/
select nome, nif
from fornecedor d
where not exists(       --fornece todas as categorias simples se der vazio--

    select nome as categoria    --todas as categorias simples--
    from categoria_simples
    except
    select categoria        --categorias fornecidas pelo fornecedor--
    from produto b
    where b.forn_primario = d.nif
);



/**** QUERY 3 ****/
select ean
from produto p 
where not exists (select ean    --nunca foi reposto se der vazio--
                    from reposicao r
                    where p.ean = r.ean);



/**** QUERY 4 ****/
select ean 
from fornece_sec
group by ean 
having count(*)>10;



/**** QUERY 5 ****/
select ean 
from reposicao 
group by ean 
having count(DISTINCT operador) = 1;  --conta operadores distintos--

