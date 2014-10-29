LaraCart
========

Laravel Cart package coded based on the Codeigniter Cart library

 * The used class was originally written by the team EllisLab Dev Team, then as I needed same 
 * class to use in laravel , I decided to modify this class and make it composer package for Laravel.
 *
 * I am also planning to add more features to this class such as
 * - Taxes 
 * - Coupons,
 * - Remove multiple items 
 * - Check if Item exists
 * - Cart SubTotal
 * - Cart Quantity : total number of items that are in the cart
 * - Cart seIdentity : Cart Identifier in case we want to use different Cart Identity in the same Session
 * - Cart getIdentity : the cart identifier.

## Introduction

The Cart package permits items to be added to a session that stays active while a user is browsing your site. These items can be retrieved and displayed in a standard "shopping cart" format, allowing the user to update the quantity or remove items from the cart.

Please note that the Cart package ONLY provides the core "cart" methodality. It does not provide shipping, credit card authorization, or other processing components.

Install this package through Composer. To your `composer.json` file, add:

```js
"require-dev": {
	"kamaro/cart": "dev-master"
}
```

Next, run `composer install --dev` to download it.

Finally, add the service provider to `app/config/app.php`, within the `providers` array.

```php
'providers' => array(
	// ...

	'Kamaro\Cart\CartServiceProvider'
)


'aliases' => array(
	// ...

	'Cart'=> 'Kamaro\Cart\Facades\Cart'
)
```

That's it! Run `php artisan` to view the three new Guard commands:

To add an item to the shopping cart, simply pass an array with the product information to the ```php  cart->insert() method, as shown below:

```php  
$data = array(
               'id'      => 'sku_123ABC',
               'qty'     => 1,
               'price'   => 39.95,
               'name'    => 'T-Shirt',
               'options' => array('Size' => 'L', 'Color' => 'Red')
            );

cart::insert($data);

```

Important: The first four array indexes above (id, qty, price, and name) are required. If you omit any of them the data will not be saved to the cart. The fifth index (options) is optional. It is intended to be used in cases where your product has options associated with it. Use an array for options, as shown above.
The five reserved indexes are:

id - Each product in your store must have a unique identifier. Typically this will be an "sku" or other such identifier.
qty - The quantity being purchased.
price - The price of the item.
name - The name of the item.
options - Any additional attributes that are needed to identify the product. These must be passed via an array.
In addition to the five indexes above, there are two reserved words: rowid and subtotal. These are used internally by the Cart class, so please do NOT use those words as index names when inserting data into the cart.

Your array may contain additional data. Anything you include in your array will be stored in the session. However, it is best to standardize your data among all your products in order to make displaying the information in a table easier.

The insert() method will return the $rowid if you successfully insert a single item.

## Adding Multiple Items to The Cart

By using a multi-dimensional array, as shown below, it is possible to add multiple products to the cart in one action. This is useful in cases where you wish to allow people to select from among several items on the same page.

```php  
$data = array(
               array(
                       'id'      => 'sku_123ABC',
                       'qty'     => 1,
                       'price'   => 39.95,
                       'name'    => 'T-Shirt',
                       'options' => array('Size' => 'L', 'Color' => 'Red')
                    ),
               array(
                       'id'      => 'sku_567ZYX',
                       'qty'     => 1,
                       'price'   => 9.95,
                       'name'    => 'Coffee Mug'
                    ),
               array(
                       'id'      => 'sku_965QRS',
                       'qty'     => 1,
                       'price'   => 29.95,
                       'name'    => 'Shot Glass'
                    )
            );

  cart::insert($data);
```

## Displaying the Cart

To display the cart you will create a view file with code similar to the one shown below.
``Note: If the quantity is set to zero, the item will be removed from the cart.``
```html

{{ Form::open(array('url' => 'ath/to/route/to/update')) }}

<table cellpadding="6" cellspacing="1" style="width:100%" border="0">

<tr>
  <th>QTY</th>
  <th>Item Description</th>
  <th style="text-align:right">Item Price</th>
  <th style="text-align:right">Sub-Total</th>
</tr>

<?php $i = 1; ?>

@foreach ($this->cart->contents() as $items):

	 {{Form::text($i.'[rowid]', $items['rowid'])}}

	<tr>
	  <td>
	  	{{Form::text(array('name' => $i.'[qty]', 'value' => $items['qty'], 'maxlength' => '3', 'size' => '5'))}}</td>
	  <td>
		{{$items['name']}}

			@if (cart::has_options($items['rowid']) == TRUE):

				<p>
					@foreach (cart::product_options($items['rowid']) as $option_name => $option_value):

						<strong>{{$option_name}}:</strong> {{$option_value}} <br />

					@endforeach; ?>
				</p>

			@endif;

	  </td>
	  <td style="text-align:right">{{cart::format_number($items['price']);}}</td>
	  <td style="text-align:right">{{cart::format_number($items['subtotal']);}}</td>
	</tr>

<?php $i++; ?>

@endforeach;

<tr>
  <td colspan="2"> </td>
  <td class="right"><strong>Total</strong></td>
  <td class="right">{{cart::format_number(cart::total())}}</td>
</tr>

</table>

<p>{{Form::submit('Update your cart')}}</p>
{{ Form::close() }}

```


## Updating The Cart

To update the information in your cart, you must pass an array containing the Row ID and quantity to the   
cart::update() method:

#### Note: If the quantity is set to zero, the item will be removed from the cart.

```php
$data = array(
               'rowid' => 'b99ccdf16028f015540f341130b6d8ec',
               'qty'   => 3
            );

cart::update($data); 

// Or a multi-dimensional array

$data = array(
               array(
                       'rowid'   => 'b99ccdf16028f015540f341130b6d8ec',
                       'qty'     => 3
                    ),
               array(
                       'rowid'   => 'xw82g9q3r495893iajdh473990rikw23',
                       'qty'     => 4
                    ),
               array(
                       'rowid'   => 'fh4kdkkkaoe30njgoe92rkdkkobec333',
                       'qty'     => 2
                    )
            );

cart::update($data);
```

### What is a Row ID?  

The row ID is a unique identifier that is generated by the cart code when an item is added to the cart. 
The reason a unique ID is created is so that identical products with different options can be managed by
 the cart.

For example, let's say someone buys two identical t-shirts (same product ID), but in different sizes. 
The product ID (and other attributes) will be identical for both sizes because it's the same shirt. 
The only difference will be the size. The cart must therefore have a means of identifying this difference so that the two sizes of shirts
 can be managed independently.
 It does so by creating a unique "row ID" based on the product ID and any options associated with it.

In nearly all cases, updating the cart will be something the user does via the "view cart" page,
so as a developer, it is unlikely that you will ever have to concern yourself with the "row ID",
other then making sure your "view cart" page contains this information in a hidden form field,
and making sure it gets passed to the update method when the update form is submitted. 
Please examine the construction of the "view cart" page above for more information.

 

### Methods Reference

```php  
cart::insert(); //Permits you to add items to the shopping cart, as outlined above.
```

```php  
cart::update(); //Permits you to update items in the shopping cart, as outlined above.
```


```php  
cart::total(); //Displays the total amount in the cart.
```

```php  
cart::total_items(); //Displays the total number of items in the cart.
```

```php  
cart::contents(); //Returns an array containing everything in the cart.
```


```php  

//Returns TRUE (boolean) if a particular row in the cart contains options. This method is designed to 
//be used in a loop with ```php  cart->contents(), since you must pass the rowid to this method, 
//as shown in the Displaying the Cart example above.

cart::has_options(rowid);
```


```php  
cart::product_options(rowid); // Returns an array of options for a particular product. This method is designed to be used in a loop with 
```

```php  
cart::contents(); // since you must pass the rowid to this method, as shown in the Displaying the Cart example above.
```

```php  

cart::destroy();//Permits you to destroy the cart. This method will likely be called when you are finished processing the customer's order.

```


Note : this package is still under development it hasn't reached it's stable version
====