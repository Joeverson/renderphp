## ---------- Como funciona ? ---------------
 
 o render é uma classe que é responsavel por renderizar paginas html adicionando informações a ela
 de forma dinamica, onde você escreve de forma estatica mas o sistema injeta informações dina-
 micamente a ela.

##----------- como chamar as funcções? ---------
 ```
 $data = [
      "cor" => "branco",
      "casa" => [
          "numero" => 123,
          "endereco" => "na rua buraco veio"
       ],
      "arco_iris" =>[
          "amarelo", "vermelho", "roxo", "cinza", "violeta"
      ]
 ]
 ```

```
Render::template("services/analise/consultor/paper.html")->view($data);
```
 
 a cima vemos a forma padraõ de chamada do render para fazer o dinamismo nas paginas,
 primeiro se chama o metodo estatico "template" onde é a localizaçaõ do arquivo que
 será renderizado com as informações dinamicas, de forma concatenada oa metodo
 "template" chamamos o metodo "view" que recebe um array com so dados a serem renderizados na
 pagina.
 
##------------- qual o padrão da pagina de tamplate? --------

 é semelhante ao do angular js onde as informações(campos do array informado em "view") é colocado
 da seguinte forma "{{cor}}" e o render ao fazer a renderização do conteudo remove o "{{cor}}" e adiciona
 "branco".

 case queira manipular um array mult-dimencilnal basta navegar com "." exp: "{{casa.numero}}" e o render
 troca para: "123"

##--------------- quero fazer loops ele faz? -------------

 Caso queira iterar uma lista ou uma array basta fazer o seguinte:

```
{{(casa)->
       <li>{{numero}}</li>
       <li>{{endereco}}</li>
 }}
 ```

 caso seja, uma array simples basta colocar "{{it}}" e ele vai traver todos os valores

```
{{(arco_iris)->
       <li>{{it}}</li>
 }}
 ```
