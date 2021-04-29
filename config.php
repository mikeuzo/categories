<?php 
define('BASEURL', $_SERVER['DOCUMENT_ROOT']. '/projectpgd/');
define('CART_COOKIE','SBwi77UCklwiqzz2');
define('CART_COOKIE_EXPIRE',time() + (86400 * 30));
define('TAXRATE', 0.007); //for America plus Sales tax rate. Set to 0 if you are not charging tax

define('CURRENCY','usd');
define('CHECKOUTMODE','TEST'); //Change TEST to LIVE when you are ready to go LIVE

if(CHECKOUTMODE == 'TEST'){
	define('STRIPE_PRIVATE','sk_test_51I9iEGKxjQZHp67QcZYkEMTKqNWGYOiVbpEuKn7scaQJOYuYHvNCVhnMoi6O7pplia5zIODUloT5RoQMHe0RIJyT00CZHoXllO');
	define('STRIPE_PUBLIC','pk_test_51I9iEGKxjQZHp67Q6nyJXJUdYaysQKRUJ3tKdG8qwaqSBfi7RcDgQYfnGhQJwMISfdC1bYOsP9e9S5UqwwIH2I7a007cGlJKVA');
}

// if(CHECKOUTMODE == 'LIVE'){
// 	define('STRIPE_PRIVATE','');
// 	define('STRIPE_PUBLIC','');
// }
?>