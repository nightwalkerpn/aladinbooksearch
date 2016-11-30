<!DOCTYPE html>
<html>
    <head>
       <meta http-equiv="content-type" content="text/xml; charset=utf-8">
       <title>알라딘 도서검색</title>
    </head>
    <body>
<?php            
        function getpubyear($paramdate)  {
            $id=0;
            $str1 = $paramdate;
            while($id<3) {
                $i = strpos($str1, ' ');
                $str2 = substr($str1,$i+1);
                $str1 = $str2;
                $id++;
            }
            $str2 = substr($str1,0,4);
            return $str2;
        }

        function displayResultPage($pagenum, $search, $querytype)    {
            $aladinquery1 = 'http://www.aladin.co.kr/ttb/api/ItemSearch.aspx?ttbkey=ttbnightwalkerpn1126001&query=';
            $aladinquery2 = '&MaxResults=10&start=';
            $aladinquery3 = '&SearchTarget=Book&output=xml&Version=20070901&Cover=Big';
            
            $finalquery = $aladinquery1 . $search . "&QueryType=" . $querytype . $aladinquery2 . $pagenum . $aladinquery3;
            echo "쿼리 : " . $finalquery . "<br>"; 

            $xmlresult = simplexml_load_file($finalquery);

            if ($xmlresult->item)   {
                $numofresult = $xmlresult->totalResults ;                
                echo "<h3> 검색결과 : " . $numofresult . " 개</h3><br>" ;
                
                foreach($xmlresult->item as $item)    {
                echo "Title : ";
                echo "<a href='" . $item->link . "'>" . $item->title . "</a><br>" ;
                echo "Author : " . $item->author . "<br>" ;
                echo "Price : ". $item->priceSales . "<br>" ;
                echo "Year : ". getpubyear($item->pubDate) . "<br>" ;
                echo "<a href='" . $item->link . "'>" ;
                echo "<img src='" . $item->cover . "' alt=cover></a><br><br>" ;
                }
            }
            else    {
                
            }
            
            return $numofresult;
        }

        if ( !empty($_POST['search']) )   {
            $search = urlencode($_POST['search']);
//            $search = $_POST['search'];
            
            switch ( $_POST['querytype'] )  {
                case 'title':
                    $querytype = 'Title' ;
                    break;
                case 'author':
                    $querytype = 'Author' ;
                    break ;
                case 'publisher':
                    $querytype = 'Publisher' ;
                    break ;
                default:
                    $querytype = 'Keyword' ;
                    break ;
            }
            
            echo "검색타입 : " . $querytype . "<br>";
            echo "검색어 : " . urldecode($search) . "<br>";
            
            $numofresult = displayResultPage($_GET['id'], $search, $querytype);
            
            if ( $numofresult ) {
                $numofpage = $numofresult/10 + 1 ;
                echo "검색결과 : " . $numofresult . "<br>";
                echo "결과페이지링크수 : " . $numofpage . "<br>";
                
                
                if ( $numofpage > 1 )   {
                    $id=0;

                    // 맨처음페이지로 링크
                    echo "<a href= BookSearchExt.php?id=1 > << </a> " ; 
                    echo " " ;

    //                while ( $id < $numofpage )    {
    //                    ;
    //                }

                    // 맨마지막 페이지로 링크
                }                
            }
            else {
                echo "검색어 : " . urldecode($search) . "<br>" ;
                echo "<h4>결과없음</h4>";
            }
        }
        else    {
            header("Location: BookSearchExt.html");
        }
?>
    </body>
</html>