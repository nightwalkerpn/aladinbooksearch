<!DOCTYPE html>
<html>
    <head>
       <meta http-equiv="content-type" content="text/xml; charset=utf-8">
       <title>알라딘 도서검색</title>
       <link rel="stylesheet" href="SearchResult.css">
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
            $aladinquery3 = '&SearchTarget=Book&output=xml&Version=20070901&Cover=Mid';
            
            $finalquery = $aladinquery1 . $search . "&QueryType=" . $querytype . $aladinquery2 . $pagenum . $aladinquery3;
            echo "<ul>쿼리 : " . $finalquery . "</ul>";

            $xmlresult = simplexml_load_file($finalquery);

            if ($xmlresult->item)   {
                $numofresult = $xmlresult->totalResults ;                
                echo "<ul>검색결과 : " . $numofresult . " 개</ul>" ;
                echo "</header>";
                foreach($xmlresult->item as $item)    {
                
                echo "<nav>";
                echo "<a href='" . $item->link . "'>" ;
                echo "<img src='" . $item->cover . "' alt=cover align='top|left'></a>" ;
                echo "</nav>";
                
                echo "<article>";    
                echo "제목 : ";
                echo "<a href='" . $item->link . "'>" . $item->title . "</a><br>" ;
                echo "저자 : " . $item->author . "<br>" ;
                echo "가격 : ". $item->priceSales . "<br>" ;
                echo "년도 : ". getpubyear($item->pubDate) . "<br>" ;
                echo "</article>";                
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
            
            echo "<header>";
            echo "<ul>검색타입 : " . $querytype . "</ul>";
            echo "<ul>검색어 : " . urldecode($search) . "</ul>";
            
            $numofresult = displayResultPage($_GET['id'], $search, $querytype);
            
            if ( $numofresult ) {
                $numofpage = $numofresult/10 + 1 ;
                
                echo "<footer>";
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
                    echo "</footer>";
                }                
            }
            else {
                echo "검색어 : " . urldecode($search) . "<br>" ;
                echo "<h4>결과없음</h4>";
                echo "</header>";
            }
        }
        else    {
            header("Location: BookSearchExt.html");
        }
?>
    </body>
</html>