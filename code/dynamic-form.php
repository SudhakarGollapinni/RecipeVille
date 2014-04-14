<?php
require_once("template.php");
myHeader();

$IngredientMenu = "";
$IngredientMenu .= '<select name="IngredientID">';
$Query = mysql_query("select * from INGREDIENT;");
$NumberOfIngredients = mysql_numrows($Query);
for ($Index = 0; $Index < $NumberOfIngredients; $Index++) {
    $IngredientID = mysql_result($Query, $Index, "IngredientID");
    $Name = mysql_result($Query, $Index, "Name");
    $Unit = mysql_result($Query, $Index, "Unit");
    $IngredientMenu .= '<option value="$IngredientID">' . $Name;
    if ($Unit != "") {
        $IngredientMenu .= ' (' . $Unit . ')';
    }
    $IngredientMenu .= '</option>';
}
$IngredientMenu .= "</select>";
?>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js" />
</script>
<script>
$(function() {
    var scntDiv = $('#p_scents');
    var i = $('#p_scents p').size() + 1;
    
    $('#addScnt').on('click', function() {
        $('<p><input type="text" id="p_scnt" size="20" name="p_scnt_' + i +'" value="" placeholder="Amount" /><?php print $IngredientMenu ?><a href="#" class="remScnt">X</a></p>').appendTo(scntDiv);
        i++;

        $('.remScnt').on('click', function() {
            $(this).parents('p').remove();
            i--;
            return false;
        });

        return false;
    });   
});
</script>

<h2><a href="#" id="addScnt">Add Ingredient</a></h2>

<div id="p_scents">
</div>

<?php
myFooter();
?>