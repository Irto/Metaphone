<?php
namespace Irto;

class Metaphone {
	public static function brazilian($STRING, $LENGTH=50){
	    /*
	     *    inicializa a chave metaf�nica
	     */
	    $META_KEY = "";

	    /*
	     *    configura o tamanho m�ximo da chave metaf�nica
	     */
	    $KEY_LENGTH = (int) $LENGTH;

	    /*
	     *    coloca a posi��o no come�o
	     */
	    $CURRENT_POS = (int) 0;

	    /*
	     *    recupera o tamanho m�ximo da string
	     */
	    $STRING_LENGTH = (int) strlen($STRING);

	    /*
	     *    configura o final da string
	     */
	    $END_OF_STRING_POS	= $STRING_LENGTH - 1;
	    $ORIGINAL_STRING	= $STRING . "    ";

	    $ORIGINAL_STRING = preg_replace('/[1|2|3|4|5|6|7|8|9|0]/',' ',$ORIGINAL_STRING);
	    $ORIGINAL_STRING = preg_replace('/[�|�|�]/','A',$ORIGINAL_STRING);
	    $ORIGINAL_STRING = preg_replace('/[�|�]/','E',$ORIGINAL_STRING);
	    $ORIGINAL_STRING = preg_replace('/[�|y]/','I',$ORIGINAL_STRING);
	    $ORIGINAL_STRING = preg_replace('/[�|�|�]/','O',$ORIGINAL_STRING);
	    $ORIGINAL_STRING = preg_replace('/[�|�]/','U',$ORIGINAL_STRING);
	    $ORIGINAL_STRING = preg_replace('/�/','SS',$ORIGINAL_STRING);
	    $ORIGINAL_STRING = preg_replace('/�/','SS',$ORIGINAL_STRING);
	    /*
	     *    Converte a string para caixa alta
	     */
	    $ORIGINAL_STRING = strtoupper($ORIGINAL_STRING);

	    /*
	     *    faz substitui��es
	     *    -> "olho", "ninho", "carro", "exce��o", "caba�a"
	     */
	    $ORIGINAL_STRING = preg_replace('/LH/','1',$ORIGINAL_STRING);
	    $ORIGINAL_STRING = preg_replace('/NH/','3',$ORIGINAL_STRING);
	    $ORIGINAL_STRING = preg_replace('/RR/','2',$ORIGINAL_STRING);
	    $ORIGINAL_STRING = preg_replace('/XC/','SS',$ORIGINAL_STRING);
	    print $ORIGINAL_STRING;
	    /*
	     *    a corre��o do SCH e do TH por conta dos nomes pr�prios:
	     *    -> "schiffer", "theodora", "ophelia", etc..
	     *
	    $ORIGINAL_STRING = preg_replace('SCH','X',$ORIGINAL_STRING);*/
	    $ORIGINAL_STRING = preg_replace('/TH/','T',$ORIGINAL_STRING);
	    $ORIGINAL_STRING = preg_replace('/PH/','F',$ORIGINAL_STRING);

	    /*
	     *    remove espa�os extras
	     */
	    $ORIGINAL_STRING = trim($ORIGINAL_STRING);

	    /*
	     *    loop principal
	     */
	    while ( strlen($META_KEY) < $KEY_LENGTH )
	    {
		/*
		 *    sai do loop se maior que o tamanho da string
		 */
		if ($CURRENT_POS >= $STRING_LENGTH)
		{
		    break;
		}

		/*
		 *    pega um caracter da string
		 */
		$CURRENT_CHAR = substr($ORIGINAL_STRING, $CURRENT_POS, 1);

		/*
		 *    se � uma vogal e faz parte do come�o da string,
		 *    coloque-a como parte da metachave
		 */
		if    ( (self::is_vowel($ORIGINAL_STRING, $CURRENT_POS)) &&
		        ( ($CURRENT_POS == 0) ||
		          (self::string_at($ORIGINAL_STRING, $CURRENT_POS-1, 1," "))
		        )
		      )
		{
		    $META_KEY .= $CURRENT_CHAR;
		    $CURRENT_POS += 1;
		}
		/*
		 *    procurar por consoantes que tem um �nico som, ou que
		 *    que j� foram substitu�das ou soam parecido, como
		 *     '�' para 'SS' e 'NH' para '1'
		 */
		elseif    ( self::string_at($ORIGINAL_STRING, $CURRENT_POS, 1,
		          array('1','2','3','B','D','F','J','K','L','M','P','T','V')) )
		{
		    $META_KEY .= $CURRENT_CHAR;

		    /*
		     *    incrementar por 2 se uma letra repetida for encontrada
		     */
		    if ( substr($ORIGINAL_STRING, $CURRENT_POS + 1,1) == $CURRENT_CHAR )
		    {
		        $CURRENT_POS += 2;
		    }

		    /*
		     *    sen�o incrementa em 1
		     */
		    $CURRENT_POS += 1;
		}
		else
		{
		    /*
		     *    checar consoantes com som confuso e similar
		     */
		    switch ( $CURRENT_CHAR )
		    {

		        case 'G':
		            switch ( substr($ORIGINAL_STRING, ($CURRENT_POS+1), 1) )
		            {
		                case 'E':
		                case 'I':
		                    $META_KEY   .= 'J';
		                    $CURRENT_POS += 2;
		                break;

		                case 'U':
		                    $META_KEY   .= 'G';
		                    $CURRENT_POS += 2;

		                break;

		                case 'R':
		                    $META_KEY .='GR';
		                    $CURRENT_POS += 2;
		                break;

		                default:
		                    $META_KEY   .= 'G';
		                    $CURRENT_POS += 2;
		                break;
		            }
		        break;

		        case 'U':
		            if ( self::is_vowel($ORIGINAL_STRING, $CURRENT_POS-1) )
		            {
		                $CURRENT_POS+=1;
		                $META_KEY   .= 'L';
		                break;
		            }
		            /*
		             *    sen�o...
		             */
		            $CURRENT_POS += 1;
		        break;

		        case 'R':
		            if (($CURRENT_POS==0)||(substr($ORIGINAL_STRING, ($CURRENT_POS-1), 1)==' '))
		            {
		                $CURRENT_POS+=1;
		                $META_KEY   .= '2';
		                break;
		            }
		            elseif (($CURRENT_POS==$END_OF_STRING_POS)||(substr($ORIGINAL_STRING, ($CURRENT_POS+1), 1)==' '))
		            {
		                $CURRENT_POS+=1;
		                $META_KEY   .= '2';
		                break;
		            }
		            elseif ( self::is_vowel($ORIGINAL_STRING, $CURRENT_POS-1) && self::is_vowel($ORIGINAL_STRING, $CURRENT_POS+1) )
		            {
		                $CURRENT_POS+=1;
		                $META_KEY   .= 'R';
		                break;
		            }
		            /*
		             *    sen�o...
		             */
		            $CURRENT_POS += 1;
		            $META_KEY   .= 'R';
		        break;

		        case 'Z':
		            if ($CURRENT_POS>=(strlen($ORIGINAL_STRING)-1))
		            {
		                $CURRENT_POS+=1;
		                $META_KEY   .= 'S';
		                break;
		            }
		            elseif (substr($ORIGINAL_STRING, ($CURRENT_POS+1), 1)=='Z')
		            {
		                $META_KEY   .= 'Z';
		                $CURRENT_POS += 2;
		                break;
		            }
		            /*
		             *    sen�o...
		             */
		            $CURRENT_POS += 1;
		            $META_KEY   .= 'Z';
		        break;


		        case 'N':
		            if (($CURRENT_POS>=(strlen($ORIGINAL_STRING)-1)))
		            {
		                $META_KEY   .= 'M';
		                $CURRENT_POS += 1;
		                break;
		            }
		            elseif (substr($ORIGINAL_STRING, ($CURRENT_POS+1), 1)=='N')
		            {
		                $META_KEY   .= 'N';
		                $CURRENT_POS += 2;
		                break;
		            }
		            /*
		             *    sen�o...
		             */
		            $META_KEY   .= 'N';
		            $CURRENT_POS += 1;
		            break;

		        case 'S':
		            /*
		             *    caso especial 'assado', 'posse', 'sapato', 'sorteio'
		             */
		            if ( (substr($ORIGINAL_STRING, ($CURRENT_POS+1), 1)=='S') ||
		                 ($CURRENT_POS==$END_OF_STRING_POS)||
		                 (substr($ORIGINAL_STRING, ($CURRENT_POS+1), 1)==' ')
		               )
		            {
		                $META_KEY .= 'S';
		                $CURRENT_POS += 2;
		            }
		            elseif (($CURRENT_POS==0)||(substr($ORIGINAL_STRING, ($CURRENT_POS-1), 1)==' '))
		            {
		                $META_KEY .= 'S';
		                $CURRENT_POS += 1;
		            }
		            elseif((self::is_vowel($ORIGINAL_STRING, $CURRENT_POS-1)) &&
		                   (self::is_vowel($ORIGINAL_STRING, $CURRENT_POS+1)))
		            {
		                $META_KEY .= 'Z';
		                $CURRENT_POS += 1;
		            }
		            /*
		            *  Ex.: Ascender, Lascivia
		            */
		            elseif (
		                        (substr($ORIGINAL_STRING, ($CURRENT_POS+1), 1)=='C') &&
		                        (
		                            (substr($ORIGINAL_STRING, ($CURRENT_POS+2), 1)=='E') ||
		                            (substr($ORIGINAL_STRING, ($CURRENT_POS+2), 1)=='I')
		                        )
		                   )

		            {
		                $META_KEY .= 'S';
		                $CURRENT_POS += 3;
		            }
		            /*
		            * Ex.: Asco, Auscutar, Mascavo
		            */
		            elseif (
		                        (substr($ORIGINAL_STRING, ($CURRENT_POS+1), 1)=='C') &&
		                        (
		                            (substr($ORIGINAL_STRING, ($CURRENT_POS+2), 1)=='A') ||
		                            (substr($ORIGINAL_STRING, ($CURRENT_POS+2), 1)=='O') ||
		                            (substr($ORIGINAL_STRING, ($CURRENT_POS+2), 1)=='U')
		                        )
		                   )

		            {
		                $META_KEY .= 'SC';
		                $CURRENT_POS += 3;
		            }
		            else
		            {
		                $META_KEY   .= 'S';
		                $CURRENT_POS += 1;
		            }
		            break;

		        case 'X':
		            /*
		             *    caso especial 't�xi', 'axioma', 'axila', 't�xico'
		             */
		            if ((substr($ORIGINAL_STRING, ($CURRENT_POS-1), 1)=='E')&&($CURRENT_POS==1))
		            {
		                $META_KEY .= 'Z';
		                $CURRENT_POS += 1;
		            }
		            elseif ((substr($ORIGINAL_STRING, ($CURRENT_POS-1), 1)=='I')&&($CURRENT_POS==1))
		            {
		                $META_KEY .= 'X';
		                $CURRENT_POS += 1;
		            }
		            elseif ((self::is_vowel($ORIGINAL_STRING, $CURRENT_POS - 1))&&($CURRENT_POS==1))
		            {
		                $META_KEY .= 'KS';
		                $CURRENT_POS += 1;
		            }
		            else
		            {
		                $META_KEY .= 'X';
		                $CURRENT_POS += 1;
		            }
		        break;

		        case 'C':
		            /*
		             *    caso especial 'cinema', 'cereja'
		             */
		            if ( self::string_at($ORIGINAL_STRING, $CURRENT_POS, 2,array('CE','CI')) )
		            {
		                $META_KEY   .= 'S';
		                $CURRENT_POS += 2;
		            }
		            elseif( (substr($ORIGINAL_STRING, ($CURRENT_POS+1), 1)=='H'))
		            {
		                $META_KEY   .= 'X';
		                $CURRENT_POS += 2;
		            }
		            else
		            {
		                $META_KEY   .= 'K';
		                $CURRENT_POS += 1;
		            }
		            break;

		        /*
		         *    como a letra 'h' � silenciosa no portugu�s, vamos colocar
		         *    a chave meta como a vogal logo ap�s a letra 'h'
		         */
		        case 'H':
		            if ( self::is_vowel($ORIGINAL_STRING, $CURRENT_POS + 1) )
		            {
		                $META_KEY .= $ORIGINAL_STRING[$CURRENT_POS + 1];
		                $CURRENT_POS += 2;
		            }
		            else
		            {
		                $CURRENT_POS += 1;
		            }
		            break;

		        case 'Q':
		           if (substr($ORIGINAL_STRING, $CURRENT_POS + 1,1) == 'U')
		           {
		              $CURRENT_POS += 2;
		           }
		           else
		           {
		              $CURRENT_POS += 1;
		           }

		           $META_KEY   .= 'K';
		           break;

		        case 'W':
		            if (self::is_vowel($ORIGINAL_STRING, $CURRENT_POS + 1))
		            {
		                $META_KEY   .= 'V';
		                $CURRENT_POS += 2;
		            }
		            else
		            {
		                $META_KEY   .= 'U';
		                $CURRENT_POS += 2;
		            }
		            break;

		        default:
		            $CURRENT_POS += 1;
		    }
		}
	    }

	    /*
	     *    corta os caracteres em branco
	     */
	    $META_KEY = trim($META_KEY);

	    /*
	     *    retorna a chave mataf�nica
	     */
	    return $META_KEY;
	}

	public static function string_at($STRING, $START, $STRING_LENGTH, $LIST){
		if ( ($START <0) || ($START >= strlen($STRING)) ){
			return 0;
		}

		for ( $I=0; $I<count($LIST); $I++){
			if ( $LIST[$I] == substr($STRING, $START, $STRING_LENGTH)){
			    return 1;
			}
		}

		return 0;
	}

	public static function is_vowel($string, $pos){
		return preg_match("/[AEIOU]/", substr($string, $pos, 1));
	}
}
?>

