<?php
/**
* Created by PhpStorm.
* User: root
* Date: 16/12/16
* Time: 15:08
 * 
 * ------------- Como funciona ? ---------------
 * 
 * o render é uma classe que é responsavel por renderizar paginas html adicionando informações a ela
 * de forma dinamica, onde você escreve de forma estatica mas o sistema injeta informações dina-
 * micamente a ela.
 * 
 * ----------- como chamar as funcções? ---------
 * 
 * $data = [
 *      "cor" => "branco",
 *      "casa" => [
 *          "numero" => 123,
 *          "endereco" => "na rua buraco veio"
 *       ],
 *      "arco_iris" =>[
 *          "amarelo", "vermelho", "roxo", "cinza", "violeta"
 *      ]
 * ]
 * 
 * Render::template("services/analise/consultor/paper.html")->view($data);
 * 
 * a cima vemos a forma padraõ de chamada do render para fazer o dinamismo nas paginas,
 * primeiro se chama o metodo estatico "template" onde é a localizaçaõ do arquivo que 
 * será renderizado com as informações dinamicas, de forma concatenada oa metodo 
 * "template" chamamos o metodo "view" que recebe um array com so dados a serem renderizados na 
 * pagina.
 * 
 * ------------- qual o padrão da pagina de tamplate? --------
 * 
 * é semelhante ao do angular js onde as informações(campos do array informado em "view") é colocado
 * da seguinte forma "{{cor}}" e o render ao fazer a renderização do conteudo remove o "{{cor}}" e adiciona
 * "branco".
 * 
 * case queira manipular um array mult-dimencilnal basta navegar com "." exp: "{{casa.numero}}" e o render
 * troca para: "123"
 * 
 * --------------- quero fazer loops ele faz? -------------
 * 
 * Caso queira iterar uma lista ou uma array basta fazer o seguinte:
 * 
 * {{(casa)->
 *       <li>{{numero}}</li>
 *       <li>{{endereco}}</li>
 * }}
 * 
 * caso seja, uma array simples basta colocar "{{it}}" e ele vai traver todos os valores
 * 
 * {{(arco_iris)->
 *       <li>{{it}}</li>
 * }}
*/

namespace libs\kernel\view;

class Render{
    private static $view;
    private static $data;


    public static function template($local)
    {
        //pegando o template e colocando em view
        $ds = DIRECTORY_SEPARATOR;
        self::$view = file_get_contents(__DIR__.$ds.$local);

        return new static;
    }

    /**
    * fazendo as subistituições dos valores no template
    **/
    private static function lookup ($match) {

        //variavel que comtem os campos do array que deseja acessar
        $ex = [];

        //limoando as blockers
        $word = str_replace("{", "", $match[0]);
            $word = str_replace("}", "", $word);

            //caso seja para pegar de um array mult-dimencional
            if(preg_match('/\./', $word) == 1){
                $ex = explode(".", $word);
            }

            // vendo qual o retrono se é um array bidimencional ou simples ate nivel 3 ex: $arr[][][]
            switch(count($ex)){
                case 2:
                return self::$data[$ex[0]][$ex[1]];
                break;
                case 3:
                return self::$data[$ex[0]][$ex[1]][$ex[2]];
                break;
                default:
                return self::$data[$word];
                break;
            }
        }

        /**
        * efecultando a spartes de looping no template
        **/
        private static function looping ($match) {

            //variavel que comtem os campos do array que deseja acessar
            $ex = [];

            //var_dump($match);

            $code = $match[2];

            //pegando o campo do array a aser iterado
            $word = str_replace("(","",$match[1]);
            $word = str_replace(")","",$word);

                //caso seja para pegar de um array mult-dimencional
                if(preg_match('/\./', $word) == 1){
                    $ex = explode(".", $word);
                }

                //var_dump($ex);

                //variavel responsavel por montar os campos qeu vão ser repetidos deacordo com as informações do array entregue
                $cc = [];

                // vendo qual o retrono se é um array bidimencional ou simples ate nivel 3 ex: $arr[][][]
                switch(count($ex)){
                    case 2:
                        foreach (self::$data[$ex[0]][$ex[1]] as $i => $d) {
                            $n_code = $code;
                            //caso seja um array ele vai pegando as informações do array qeu foi dita pelo usuario no template
                            if(is_array($d)){

                                foreach(array_keys($d) as $kk){
                                    $pattern = '/{{'.$kk.'}}/';

                                    if(preg_match($pattern, $n_code) == 1){
                                        $n_code = preg_replace($pattern, $d[$kk], $n_code);
                                    }

                                }

                            }else{//caso não seja um array ele vai imprlimindo de acordo com o que tem
                                //verificando se existe um 'it' (usado para imprimir o que tiver no array sem dar a key)
                                if(preg_match('/{{it}}/', $n_code) == 0)
                                    $pattern = '{{'.$i.'}}';
                                else
                                    $pattern = '{{it}}';

                                $n_code = str_replace($pattern, $d, $n_code);
                            }


                            $cc[] = $n_code;
                        }
                        return implode(" ", $cc);
                        break;
                    case 3:
                        foreach (self::$data[$ex[0]][$ex[1]][$ex[2]] as $i => $d) {
                            $n_code = $code;
                            //caso seja um array ele vai pegando as informações do array qeu foi dita pelo usuario no template
                            if(is_array($d)){

                                foreach(array_keys($d) as $kk){
                                    $pattern = '/{{'.$kk.'}}/';

                                    if(preg_match($pattern, $n_code) == 1){
                                        $n_code = preg_replace($pattern, $d[$kk], $n_code);
                                    }

                                }

                            }else{//caso não seja um array ele vai imprlimindo de acordo com o que tem
                                //verificando se existe um 'it' (usado para imprimir o que tiver no array sem dar a key)
                                if(preg_match('/{{it}}/', $n_code) == 0)
                                    $pattern = '{{'.$i.'}}';
                                else
                                    $pattern = '{{it}}';

                                $n_code = str_replace($pattern, $d, $n_code);
                            }


                            $cc[] = $n_code;
                        }

                        return implode(" ", $cc);
                        break;
                    default:
                        foreach (self::$data[$word] as $i => $d) {
                            $n_code = $code;
                            //caso seja um array ele vai pegando as informações do array qeu foi dita pelo usuario no template
                            if(is_array($d)){

                                foreach(array_keys($d) as $kk){
                                    $pattern = '/{{'.$kk.'}}/';

                                    if(preg_match($pattern, $n_code) == 1){
                                        $n_code = preg_replace($pattern, $d[$kk], $n_code);
                                    }

                                }

                            }else{//caso não seja um array ele vai imprlimindo de acordo com o que tem
                                //verificando se existe um 'it' (usado para imprimir o que tiver no array sem dar a key)
                                if(preg_match('/{{it}}/', $n_code) == 0)
                                    $pattern = '{{'.$i.'}}';
                                else
                                    $pattern = '{{it}}';

                                $n_code = str_replace($pattern, $d, $n_code);
                            }


                            $cc[] = $n_code;
                        }
                        return implode(" ", $cc);
                    break;
                }
            }


            public function view($data=null)
            {
                if($data == null){
                    print self::$view;
                    die();
                }

                //dados que foram dados pelo usuário
                self::$data = $data;

                //procurando por loooping
                $pattern = '/{{(\(.*\))->([ \_* | \w* | \d* | \s* | áàâãéèêíïóôõöúçñÁÀÂÃÉÈÍÏÓÔÕÖÚÇÑ* | \$\&\+\,\:\=\?\@\#\'\<\>\.\^\*\(\)\%\!\- * | {}* | \<\> * | \<\/\> * | \/ | \n * ]*)}};/';

                //$stop=0; //variavel de controlle para não dar looping infinito;

                /**
                 * fazendo uma analise se existe loops para serem renderizados
                 * de forma recursiva (foi o jeito que achei para resolver o problema dele pegar tudo de
                 * uma vez e não conseguir trabalhar);
                **
                do{
                    if($stop == 30)
                        break;

                    self::$view = preg_replace_callback($pattern, "self::looping", self::$view);

                    $stop++;
                }while(preg_match($pattern, self::$view) == 1);*/

                self::$view = preg_replace_callback($pattern, "self::looping", self::$view);

                /**
                 * pegando o tamplate e fazendo a substituição
                 **/
                //criando os padroes e mandando ele subistituir
                foreach($data as $i => $d){
                    $pattern = '/({{'.$i.'.*}}|{{'.$i.'.*.*}}|{{'.$i.'}})/';
                    self::$view = preg_replace_callback($pattern, "self::lookup", self::$view);
                }


                //jogando na tela o que foi renderizado.
                print self::$view;
            }
        }

