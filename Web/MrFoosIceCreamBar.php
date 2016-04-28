
<!DOCTYPE html>
<html>
    <head>
    <title>Mr. Foo's Ice Cream Bar</title>
    </head>
    <body>
<DIV ALIGN=CENTER>
<h1> Mr. Foo's Ice Cream Bar </h1>
<img src="https://upload.wikimedia.org/wikipedia/commons/c/cb/Foo_was_here.jpg">
<br><br>
<DIV STYLE="font-family: Arial Black; 
font-size: 20px; color: black">
<?php 
/**

 */
class orderIC
{
    public $discount = 0.0;
    var $priceTot = 0.0;
    var $scoops = 0.0;
    
    public $orderType = array('Ice Cream Scoop'=>0, 'Soda Float'=>1.0, 'Milk Shake'=>1.0 );
    //This should be common to all order types, price info included
    
    public $iceCream = array('Chocolate'=>1.0, 'Vanilla'=>1.0, 'Mint Chip'=>1.0, 'Neopolitan'=>1.0, 'Rocky Road'=>1.0);
    //These get overloaded for the specific order types
    public $medium = array('Cake'=> 1.0, 'Waffle'=>1.25, 'Cup'=>1.0 );
    //These get overloaded for the order
    var $choices = array();
    
    public function displayVar($var)
    {
        var_dump( $this->iceCream);
    }
    
    public function addType($flavor)
    {
        $this->choices[$flavor]= $this->orderType[$flavor];
    }
    public function addMedium($flavor)
    {
            $this->choices[$flavor]= $this->medium[$flavor];
    }
    
    public function addScoop($flavor)
    
    {
            $this->scoops=  $this->scoops+1;
            $flavor1 = "Scoop ".$this->scoops." of ". $flavor;
            $this->choices[$flavor1]= $this->iceCream[$flavor];
    }
    
    public function RingTotal()
    {
        $this->priceTot =0; 
        foreach($this->choices as $x =>$XP){
            echo $x . "    costs:    $". (float)$XP ."<br>";
            $this->priceTot =   $this->priceTot + $XP;
            
        }
        echo "Total is:                  $". $this->priceTot."<br>";
        if ($this->discount != 0){
            
            echo "<b> Discounted Total is:                  </b>$". $this->priceTot*(1 - $this->discount)."<br>";

            
        }
    }
}
/*These are its kids*/
class icScoop extends orderIC
{
    //This is just the defaiult, but since the other kids get to be overloaded,  should to be fair to all the kids
     public $medium = array('Cake'=> 1.0, 'Waffle'=>1.25, 'Cup'=>1.0 );
     public $discount = 0.0; 
}

class sodaFloat extends orderIC
{
    public $medium = array('Root Beer'=> 0, 'Coke'=>0, 'Orange Crush'=>0);
    public $discount = 0.1; 
}

class milkShake extends orderIC
{
    public  $medium= array('Whole'=> 0,'2%'=> 0, 'Skim'=> 0);
    public $discount = 0.2;
    
}

//Make a basic web interface to test the class

$var_value = $_POST['flavor'];
$screen = $_POST['screen'];
if (! isset($screen)){
$screen =  1;
};

//Screen switching logic
            switch ($screen){
            case '1':  
                //create a Screen
                 echo 'Choose your treat' ;
                $a=new orderIC();
                $orderType = $a->orderType;
                break;
            case '2':
                switch ($var_value){
                case 'Ice Cream Scoop':
                    $a= new  icScoop();
                    break;
                case 'Soda Float' :
                    $a= new  sodaFloat();
                    break;
                case 'Milk Shake':
                    $a= new  milkShake();
                    break;
                default:
                    echo 'wrong treat';
                    unset($var_value);
                }
                $a->addType($var_value);
                echo 'Got it, what would you like with your ', $var_value ,'? <br>' ;
                $orderType= $a->medium;
                apc_store('a',$a, 60);
                break;
            case '3':
                $a=apc_fetch ('a');
                if(isset($var_value))$a->addMedium($var_value);
                apc_store('a',$a, 60);
                echo 'What kind of Ice Cream would you like? <br>' ;
                $orderType= $a->iceCream;
                break;
            case '4':
                $a=apc_fetch ('a');
                $a->addScoop($var_value);
                apc_store('a',$a, 60);
                echo 'Would you like another Scoop? <br>' ;
                echo   "<form action='/MrFoosIceCreamBar.php' method='POST'>";
                echo   "<input type='hidden' name='screen' value='3'>";
                echo   "<input type='submit'  value='YES!' style='font-size:20px'>";
                echo  "</form>";
                
                echo   "<form action='/MrFoosIceCreamBar.php' method='POST'>";
                echo   "<input type='hidden' name='screen' value='5'>";
                echo   "<input type='submit'  value='Nah' style='font-size:20px'>";
                echo  "</form>";
                $orderType= null;
                break;
            case '5':
                $a=apc_fetch ('a');
                $a->RingTotal();
                echo   "<form action='/MrFoosIceCreamBar.php' method='POST'>";
                echo   "<input type='hidden' name='screen' value='",'1',"'>";
                echo   "<input type='submit'  value='New Order'style='font-size:20px'>";
                echo   "</form>";
                $orderType= null;
                apc_delete('a');
                break;   
            default:
                echo   "<form action='/MrFoosIceCreamBar.php' method='POST'>";
                echo   "<input type='hidden' name='screen' value='",'1',"'>";
                echo   "<input type='submit'  value='Start Over' style='font-size:20px'>";
                echo   "</form>";
                $orderType= null;
                apc_delete('a');;
            }
         
function makeScreen( $genArray, $scrNum){
        $scrNum =$scrNum +1;
        echo   "<form action='/MrFoosIceCreamBar.php' method='POST'>";
       
        echo   "<input type='hidden' name='screen' value='",$scrNum,"'>";
        foreach($genArray as $x =>$XP)
        {
        echo   "<input type='submit' name='flavor' value='" , $x ,"' style='font-size:20px'>";
        }
        echo   "</form>";
}
makeScreen($orderType,$screen);


?>
</DIV>
</DIV>
    </body>
</html>
