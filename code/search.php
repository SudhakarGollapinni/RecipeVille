<?php
require_once("template.php");
myHeader();

echo "<h2>Search</h2>";
echo '<form action="search.php" method="get">';
echo 'Name:<input type="text" name="Name"><br />';
echo 'Category:<select name="Category">';
$categoryquery = "select Name from CATEGORY where SuperCategoryID is not null";
$categoryresult = mysql_query($categoryquery);
$numcats = mysql_numrows($categoryresult);
echo '<option value=" "> </option>';
for ($i = 0; $i < $numcats; $i++) {
  $categoryname = mysql_result($categoryresult, $i, $_CATEGORY_NAME_FIELD);
  echo "<option value='$categoryname'>$categoryname</option>";
}
echo '</select><br />';

echo 'Difficulty:<select name="Difficulty">';
echo '<option value=" "></option>';
echo '<option value="easy">Easy</option>';
echo '<option value="medium">Medium</option>';
echo '<option value="hard">Hard</option>';
echo '</select><br />';
echo 'Tags (space separated): <input type="text" name="Tags"><br />';
echo 'Ingredients: <input type="text" name="Ingredients">';
echo '<br>';
echo '<input name="Search" type="submit" value="Search">';
echo '<br>';
echo '</form>';

$searchquery = "select DISTINCT RECIPE.RecipeID, RECIPE.Name, Photo from ";

$Name = htmlspecialchars(mysql_real_escape_string($_GET["Name"]));
if (isset($_GET["Name"]) && $Name != '') {
  $conditions[] .= "name LIKE '%$Name%' ";
  $tables[] .= " RECIPE ";
}

$Category = htmlspecialchars(mysql_real_escape_string($_GET["Category"]));
if (isset($_GET["Category"]) && $Category != ' ') {
  $conditions[] .= " CATEGORY.Name LIKE '%$Category%' AND CATEGORY.CategoryID = RECIPE_CATEGORY.CategoryID AND RECIPE_CATEGORY.RecipeID = RECIPE.RecipeID ";
  $tables[] .=" RECIPE ";
  $tables[] .=" RECIPE_CATEGORY ";
  $tables[] .=" CATEGORY ";
}

$Difficulty = htmlspecialchars(mysql_real_escape_string($_GET["Difficulty"]));
if (isset($_GET["Difficulty"]) && $Difficulty != ' ') {
  $tables[] .= " RECIPE ";
  $conditions[] .= " difficulty LIKE '%$Difficulty%' ";
}

$Tags = htmlspecialchars(mysql_real_escape_string($_GET["Tags"]));
if (isset($_GET['Tags']) && $Tags != '') {
  $rt = explode(' ', $Tags);
  $tables[] .= " RECIPE ";
  foreach($rt as $r) {
    $conditions[] .= " RECIPE.Tags LIKE '%$r%' ";
  }
} 

$Ingredients = htmlspecialchars(mysql_real_escape_string($_GET["Ingredients"]));
if ($Ingredients != '') {
  $ri = explode(' ', $Ingredients);
  $tables[] .= " INGREDIENT ";
  $tables[] .= " RECIPE_INGREDIENT ";
  $tables[] .= " RECIPE ";
  foreach ($ri as $i) {
    $Iconditions[] .= " INGREDIENT.Name LIKE '%$i%' "; 
  }
  $conditions[] .= "(" . implode('OR', $Iconditions) . ")";
  $conditions[] .= " INGREDIENT.IngredientID = RECIPE_INGREDIENT.IngredientID AND RECIPE_INGREDIENT.RecipeID = RECIPE.RecipeID ";
} else if ($Ingredients == '' && $Tags == '' && $Name == '' && $Difficulty == ' ' && $Category == ' ') {
   echo "<p id='alert'>Enter any one search criteria to obtain results.</p>";
}

$uniquetables = array_unique($tables, SORT_REGULAR);
$searchquery .= implode(',',$uniquetables) . " WHERE ";
$searchquery .= implode('AND', $conditions);

$Query = mysql_query($searchquery);
$NumberOfResults = mysql_numrows($Query);

if($NumberOfResults==0 && isset($_GET["Search"]))
{echo "No Results found";}
for ($Index = 0; $Index < $NumberOfResults; $Index++) {
  $RecipeID = mysql_result($Query, $Index, "RecipeID");
  $Photo = mysql_result($Query, $Index, "Photo");
  if ($Photo == "") {
    $Photo = "food.png";
  }
  $Name = mysql_result($Query, $Index, "Name");
  print "<a class='recipe' href='recipe.php?RecipeID=$RecipeID'><img alt='$Name' src='http://www.cs.indiana.edu/cgi-pub/ssrirang/upload/$Photo' width='128px' /><span class='recipe-name'>