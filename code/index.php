<?php
require_once("template.php");
myHeader();
print "<img alt='Splash' id='splash' src='splash.jpg' width='300px' />
<!-- Source: http://www.flickr.com/photos/webb-zahn/5169215261/ -->
<p id='tagline'>Welcome to Recipeville –– your source for the best recipes!</span>
<p>We specialize in healthy recipes for people with dietary restrictions such as dairy-free, diabetes, gluten-free, and low cholesterol. You can browse the recipes by selecting a category or search for recipes by difficulty, tags, and ingredients. Please feel free to review our recipes so that we can improve the website further.</p>";

$query = "select name from CATEGORY where CATEGORY.SuperCategoryID is NULL;";
$query_cid="select CategoryID from CATEGORY where CATEGORY.SuperCategoryID is NULL;";
$result = mysql_query($query);
$result_cid=mysql_query($query_cid);
$num = mysql_numrows($result);

for ($i=0; $i < $num; $i++) {
$supercategoryname = mysql_result($result, $i, $_CATEGORY_NAME_FIELD);
$supercategoryid = mysql_result($result_cid, $i, $_CATEGORY_CategoryID_FIELD);

echo "<h3>$supercategoryname</h3>";

$query1="select CATEGORY.Name from CATEGORY where CATEGORY.Supercategoryid=$supercategoryid order by CATEGORY.Name;";
$result1=mysql_query($query1);
$numcats=mysql_numrows($result1);
//echo "num = $numcats";
  for($j=0;$j<$numcats;$j++)
  {
  // echo "num = $numcats";
   $categoryname=mysql_result($result1, $j, $_CATEGORY_NAME_FIELD);
   $idquery="select CategoryID from CATEGORY where Name='$categoryname';";
   $idresult=mysql_query($idquery);
   
   $categoryid=mysql_result($idresult, 0, $_CATEGORY_CATEGORYID_FIELD); 
   echo "<a href='category.php?CategoryID=$categoryid'>$categoryname</a>";
   if ($j < $numcats - 1) {
   		print " &bull; "; 
   }
  }
}
myFooter();
?>
