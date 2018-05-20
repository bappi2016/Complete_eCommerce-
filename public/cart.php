<?php require_once("../resources/config.php");?>

<?php 
if(isset($_GET['add'])){
  $Query = query("SELECT * FROM products WHERE product_id=" .escape_string( $_GET['add']) . " " );
  confirm($Query);
  while($Datarows=fetch_array($Query)){
    $No_of_product = $Datarows['product_quantity'];
    if($No_of_product!= $_SESSION['product_' . $_GET['add']]){
      $_SESSION['product_'.$_GET['add']] +=1;
      redirect("checkout.php");
    }
    else{
      set_message("Sorry We only have " . $No_of_product." available product");
      redirect("checkout.php");
    }


  }
}


if(isset($_GET['remove'])){
  $_SESSION['product_'.$_GET['remove']] -=1 ; 
  if($_SESSION['product_'.$_GET['remove']] < 1) ;{
    unset($_SESSION['Item_Quantity']);
    unset($_SESSION['Item_Total']);
    redirect("checkout.php");
  }

}


if(isset($_GET['delete'])){
  $_SESSION['product_'.$_GET['delete']] ='0' ; 
  unset($_SESSION['Item_Quantity']);
  unset($_SESSION['Item_Total']);
  redirect("checkout.php");

}


function get_cart(){
  $Total = 0;
  $Item_Quantity = 0;
  foreach($_SESSION as $product => $value){

    if($value>0){
      if(substr($product,0,8)=="product_"){
        $length = strlen($product - 8);
        echo $length;
        $id = substr($product, 8 ,$length);
        echo $id;

         #call the query($sql_statement) function
  $query = query("SELECT * FROM products WHERE product_id=".escape_string($id)." ");
  confirm($query);
  while($DataRows = fetch_array($query)){
  
   $Id = $DataRows['product_id'];
   $Title = $DataRows['product_title'];
   $P_Cat_Id = $DataRows['product_category_id'];
   $Price = $DataRows['product_price'];
   $Description = $DataRows['product_description'];
   $P_Image = $DataRows['product_image'];
   $SubTotal = $Price*$value;
   $Item_Quantity += $value; 

   #Heredoc allows a multi-line string to be easily assigned to variables or echoed. Heredoc text behaves just like a string without the double quotes.

   $show_cart = <<<DELIMETER
   

  <tr>
  <td>{$Title}</td>
  <td>&#36;{$Price}</td>
  <td>{$value}</td>
  <td>&#36;{$SubTotal}</td>
  <td><a class="btn btn-warning btn-sm" href="cart.php?remove={$Id}"> <span class= "glyphicon glyphicon-minus" ></span></a>
  <a class="btn btn-primary btn-sm" href="cart.php?add={$Id}"> <span class= "glyphicon glyphicon-plus" ></span></a>
  <a class="btn btn-danger btn-sm" href="cart.php?delete={$Id}"> <span class= "glyphicon glyphicon-remove" ></span></a></td>
  
</tr>

DELIMETER;
echo $show_cart;



      }
      $_SESSION['Item_Total'] = $Total+=$SubTotal;
      $_SESSION['Item_Quantity'] = $Item_Quantity;
      
    }
  }
}
}

?>