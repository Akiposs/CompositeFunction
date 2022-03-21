<?php 

//関数型パラダイムにおける合成関数のサンプル
class Sample{
    public function main(){
        
        // 関数を合成する
        //「第一引数で渡された関数で処理した後に、第二引数で渡された関数で処理する」関数を返す
        // 関数は値と処理で構成される。
        // オブジェクト指向だと処理は固定で値だけが外部から渡されるが、これは処理も外部から渡されている
        // array_reduceにかけるとcurryの部分にネストされた関数呼び出しがくる
        $compose = function(callable $curry, callable $callback):callable {
            //...記法で引数定義を行うと可変長引数になり、幾つでも引数を渡すことができるようになる
            return function(...$arguments) use ($callback, $curry){
                //処理側に書いた...記法は配列を展開する。つまり引数定義...で配列型になってしまった引数等を元に戻す
                return $callback($curry(...$arguments));
            };
        };

        //array_reduceを用いて渡された関数を再帰的に合成する関数
        $comoses = function($function, ...$functions) use ($compose){
            return array_reduce(
                $functions,
                $compose,
                $function,
            );
        };

        $plusOne = function(int $int, int $int2){
            return $int+ $int2 + 1;
        };

        $plusTwo = function(int $int, int $int2){
            return $int+ $int2+2;
        };

        $timesTwo = function(int $int, int $int2){
            return $int+ $int2*2;
        };

        $minusOne = function(int $int, int $int2){
            return $int+ $int2-1;
        };

        //composes()によって関数が合成し、3足して2倍する関数を定義
        $plusThreeAndTimesTwo = $comoses($plusOne, $plusTwo, $timesTwo);
        

        // ここで帰っているのは、以下の形のfunctionになるため、このまま引数を渡すことができる
        // function(...$arguments) use ($callback, $curry){
        //     return $callback($curry(...$arguments));
        // };
        echo $plusThreeAndTimesTwo(30, 2);
        // 70
    }
}