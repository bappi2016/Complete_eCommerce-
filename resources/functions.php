<?php

# HELPER FUNCTION SECTION # 


function redirect($location){
  header("Location: $location");
}

function query($sql_statement){
  global $connection;
  return mysqli_query($connection, $sql_statement);
}

function confirm($result){
global $connection;
if(!$result){
  die("QUERY FAILED" . mysqli_error($connection));
}
}


function escape_string($string){
  global $connection;
  return mysqli_real_escape_string($connection, $string);
}

function fetch_array($result){
  return mysqli_fetch_array($result);
}


# END OF  HELPER FUNCTION SECTION # 



/************************** FRONT END FUNCTION *****************************/


function get_products(){

  #call the query($sql_statement) function
  $query = query("SELECT * FROM products");
  confirm($query);
  while($DataRows = fetch_array($query)){
  
   $Id = $DataRows['product_id'];
   $Title = $DataRows['product_title'];
   $P_Cat_Id = $DataRows['product_category_id'];
   $Price = $DataRows['product_price'];
   $Description = $DataRows['product_description'];
   $P_Image = $DataRows['product_image'];

   #Heredoc allows a multi-line string to be easily assigned to variables or echoed. Heredoc text behaves just like a string without the double quotes.

   $product = <<<DELIMETER

   <div class="col-sm-4 col-lg-4 col-md-4">
    <div class="thumbnail">
     <a href="item.php?id={$Id}"><img src="http://placehold.it/320x150" alt=""></a>
        <div class="caption">
            <h4 class="pull-right">&#36;{$Price}</h4>
            <h4><a href="item.php?id={$Id}">{$Title}</a>
            </h4>
            <p>{$Description}</p>
        </div>
        <a class="btn btn-primary" target="_blank" href="cart.php?add={$Id}">ADD TO CART</a>
        
    </div>
</div>

                    
DELIMETER;
echo $product;
}
}



function get_categories(){


  $query = query("SELECT * FROM categories");
  confirm($query);
  while($DataRows = fetch_array($query)){
  $Category_Id = $DataRows['cat_id'];
  $Category_title = $DataRows['cat_title'];

  $category_links = <<<DELIMETER


<a href='category.php?id={$Category_Id}' class='list-group-item'>{$Category_title}</a>

DELIMETER;

echo $category_links;

}

}


function get_product_in_category_page(){

  #call the query($sql_statement) function
  $query = query("SELECT * FROM products WHERE product_category_id= " . escape_string($_GET['id']) . " ");
  confirm($query);
  while($DataRows = fetch_array($query)){
  
   $Id = $DataRows['product_id'];
   $Title = $DataRows['product_title'];
   $P_Cat_Id = $DataRows['product_category_id'];
   $Price = $DataRows['product_price'];
   $Description = $DataRows['product_description'];
   $P_Image = $DataRows['product_image'];

   #Heredoc allows a multi-line string to be easily assigned to variables or echoed. Heredoc text behaves just like a string without the double quotes.

   $product = <<<DELIMETER

  <div class="col-md-3 col-sm-6 hero-feature">
      <div class="thumbnail">
          <img src="http://placehold.it/800x500" alt="">
          <div class="caption">
              <h3>Feature Label</h3>
              <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
              <p>
                  <a href="#" class="btn btn-primary">Buy Now!</a> <a href="item.php?id={$Id}" class="btn btn-default">More Info</a>
              </p>
          </div>
      </div>
    </div>

                    
DELIMETER;
echo $product;
}
}


function get_product_in_shop_page(){

  #call the query($sql_statement) function
  $query = query("SELECT * FROM products");
  confirm($query);
  while($DataRows = fetch_array($query)){
  
   $Id = $DataRows['product_id'];
   $Title = $DataRows['product_title'];
   $P_Cat_Id = $DataRows['product_category_id'];
   $Price = $DataRows['product_price'];
   $Description = $DataRows['product_description'];
   $P_Image = $DataRows['product_image'];

   #Heredoc allows a multi-line string to be easily assigned to variables or echoed. Heredoc text behaves just like a string without the double quotes.

   $product = <<<DELIMETER

  <div class="col-md-3 col-sm-6 hero-feature">
      <div class="thumbnail">
          <img src="http://placehold.it/800x500" alt="">
          <div class="caption">
              <h3>Feature Label</h3>
              <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
              <p>
                  <a href="#" class="btn btn-primary">Buy Now!</a> <a href="item.php?id={$Id}" class="btn btn-default">More Info</a>
              </p>
          </div>
      </div>
    </div>

                    
DELIMETER;
echo $product;
}
}

function user_login(){
if(isset($_POST['submit'])){
  $user_name = escape_string($_POST['username']) ;
  $password = escape_string($_POST['password']) ;

$query = query("SELECT * FROM users WHERE username = '{$user_name}' AND password = '{$password}'");
confirm($query);
if(mysqli_num_rows($query)==0){
  set_message("Your Password or Username are wrong");
redirect("login.php");
}
else{
   set_message("Welcome to Admin {$user_name}");
  redirect("admin");
}
}
}


function set_message($msg){
  if(!empty($msg)){
    $_SESSION['message'] = $msg;
  }else{
    $msg = "";
  }
}

function display_message(){
  if(isset($_SESSION['message'])){
    echo $_SESSION['message'];
    unset($_SESSION['message']);
  }
}


function send_message(){

  if(isset($_POST['submit'])){
    $TO = "bappi.ajmalaamir@gmail.com";
    $messenger_name = $_POST['name'];
    $messenger_email = $_POST['email'];
    $messenger_subject = $_POST['subject'];
    $messenger_message = $_POST['message'];
    $headers = "From: {$messenger_name} {$messenger_email}";


    ini_set("SMTP","ssl://smtp.gmail.com"); 
    ini_set("smtp_port","587"); 
    ini_set("sendmail_from","bappi013@gmail.com");
    ini_set("sendmail_path", "\"C:\xampp\sendmail\sendmail.exe\" -t"); 


    $result =  mail($TO,$messenger_subject,$messenger_message,$headers);

    if(!$result){
    set_message("Sorry we could not send your message");
    }
    else{
      set_message("Your message has been sent");
    }


  }
}




?>