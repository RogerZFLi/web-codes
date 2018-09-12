<DOCTYPE html>
<html>
<body>
	<?php
		class Menuitem {
			private $itemName, $description, $price;
			function __construct($iName,$de,$pr){
				$this->itemName = $iName;
				$this->description = $de;
				$this->price = $pr;
			}
			function get_itemName(){
				return $this->itemName;
			}
			function get_description(){
				return $this->description;
			}
			function get_price(){
				return $this->price;
			}	
		}
	?>
</body>
</html>
