<?php
/**
 * Dokumentacija za plugin.
 *
 * @package image-haddad-wp/settings
 */

// HTML "TABELA".
/**
 * Container html tag start
 *
 * @param string $class - list of css classes.
 * @return html
 */
function ihwp_contain_st( $class = 'container' ) {
	return '<div class="' . $class . '">';
}
/**
 * Container html tag end
 *
 * @return html
 */
function ihwp_contain_end() {
	return '</div>';
}
/**
 * HTML "table" row.
 *
 * @param string $class - CSS classes.
 * @param string $inner - inner tag.
 * @param string $content - html content.
 * @param string $style - style attribute(s).
 * @return html
 */
function ihwp_docdiv( $class = '', $inner = '', $content = '', $style = '' ) {
	$classtag = $class ? ' class="' . $class . '"' : '';
	$styletag = $style ? ' style="' . $style . '"' : '';
	$inner_st = $inner ? '<' . $inner . '>' : '';
	$innerend = $inner ? '</' . $inner . '>' : '';
	return '<div' . $classtag . $styletag . '>' . $inner_st . $content . $innerend . '</div>';
}

// Header.
$doc_html  = ihwp_contain_st();
$doc_html .= '<h3>Na ovoj stranici je dokumentiran kod, funkcije i funkcionalnosti plugina "Image Haddad WP", a odnosi se na customizacije dodataka (pluginova) i tema na www.haddad.hr stranici. Ovdje ćete naći informaciju koji su hookovi sa pripadajućim funkcijama, te koje su ostale funkcije vezane za koju prilagodbu.</h3>';
$doc_html .= ihwp_contain_end();

// Subheader.
// $doc_html .= ihwp_contain_st();
// $doc_html .= ihwp_docdiv( '', '', '<i>Pretraži funkcije:</i>', 'width:50%' );
// $doc_html .= ihwp_contain_end();


// Opisi funkcija.
$doc_html .= ihwp_contain_st( 'container light' );
$doc_html .= ihwp_docdiv( 'title', 'h4', 'Prilagodba' );
$doc_html .= ihwp_docdiv( 'desc', 'p', 'Opis' );
$doc_html .= ihwp_docdiv( 'scope', 'p', 'Vrsta' );
$doc_html .= ihwp_contain_end();

// CUSTOM STATUSI NARUDZBI.
$doc_html .= ihwp_contain_st();
$doc_html .= ihwp_docdiv( 'title', 'h4', 'Custom statusi narudžbi' );
$doc_html .= ihwp_docdiv( 'desc', 'p', 'Dodatni statusi WooCommerce narudžbi - <code>"Nova narudžba"</code> (wc-new-order), <code>"Oprez!"</code>(wc-caution) i <code>"U izradi"</code> (wc-in-creation). "Nova narudžba" se postavlja kao status narudžbe na stranici potvrde narudžbe (Narudžba zaprimljena). <br>Hookovi: <code>woocommerce_register_shop_order_post_statuses</code>, <code>woocommerce_thankyou</code>, <code>wc_order_statuses</code>, <code>views_edit-shop_order</code>, <code>bulk_actions-edit-shop_order</code>, <code>admin_menu</code>.' );
$doc_html .= ihwp_docdiv( 'scope', 'p', 'Global' );
$doc_html .= ihwp_contain_end();

// CUSTOM FUNKCIJE ZA KUPONE.
$doc_html .= ihwp_contain_st();
$doc_html .= ihwp_docdiv( 'title', 'h4', 'Custom funkcije kupona' );
$doc_html .= ihwp_docdiv( 'desc', 'p', 'Dodatni meta box u adminu pojedinog kupona za privremeno isključivanje cachea prikaza kuponske cijene van košarice:<br>Hook:<code>add_meta_boxes</code>, Funkcija: <code>haddad_coupon_price_cache</code><hr>Dodatni tab kupona "Haddad postavke"<br> - Postavka za uključivanje prikaza kuponskih cijena na proizvodima u katalogu/kategorijama<br>- Postavka za biranje pozadinske boje prikaza pojedinačnog kupona<br>Hook:<code>woocommerce_coupon_data_tabs</code> - Funkcija:<code>haddad_coupon_data</code><br>Hook:<code>woocommerce_coupon_data_panels</code> - Funkcija <code>haddad_coupon_data_panel</code>; Hook:<code>woocommerce_process_shop_coupon_meta</code> - Funkcija <code>haddad_coupon_save_data</code>' );
$doc_html .= ihwp_docdiv( 'scope', 'p', 'Global' );
$doc_html .= ihwp_contain_end();

// PRODUCT SEARCH LIMIT.
$doc_html .= ihwp_contain_st();
$doc_html .= ihwp_docdiv( 'title', 'h4', 'Limit proizvoda za search' );
$doc_html .= ihwp_docdiv( 'desc', 'p', 'WooCommerce admin limit za traženje proizvoda - limit stavljen zbog performansi admin stranice.<br>Hook: <code>woocommerce_json_search_limit</code>. Limit je postavljen na max. 150 proizvoda.' );
$doc_html .= ihwp_docdiv( 'scope', 'p', 'Admin' );
$doc_html .= ihwp_contain_end();

// INCLUDE WC TAXONOMY TERMS IN SEARCH.
$doc_html .= ihwp_contain_st();
$doc_html .= ihwp_docdiv( 'title', 'h4', 'Uključivanje WC taksonomija (pojmova) u search' );
$doc_html .= ihwp_docdiv( 'desc', 'p', 'WooCommerce defaultni search proširen sa mogućnošću traženja pomoću pojmova taksonomija (kategorija-tagova) u traženje<br>Hook: <code>posts_search</code> - funkcija <code>woocommerce_search_product_tag_extended</code><br>Class datoteka: <code>public/class-image-haddad-wp-public.php</code>' );
$doc_html .= ihwp_docdiv( 'scope', 'p', 'Frontend' );
$doc_html .= ihwp_contain_end();

// AUTOMATSKI TERM NA SPREMANJU PROIZVODA.
$doc_html .= ihwp_contain_st();
$doc_html .= ihwp_docdiv( 'title', 'h4', 'Automatska kategorija kod spremanja proizvoda' );
$doc_html .= ihwp_docdiv( 'desc', 'p', 'Kod prvog spremanja proizvoda, proizvod je dodijeljen kategoriji "Novo u web shopu" (slug novo-u-web-shopu).<br>Hook: <code>draft_to_publish</code> - Funkcija: <code>haddad_assign_term_on_save</code>.' );
$doc_html .= ihwp_docdiv( 'scope', 'p', 'Admin' );
$doc_html .= ihwp_contain_end();

// CRON JOBS.
$doc_html .= ihwp_contain_st();
$doc_html .= ihwp_docdiv( 'title', 'h4', 'Automatsko brisanje kategorije (cron job)' );
$doc_html .= ihwp_docdiv( 'desc', 'p', 'Automatsko brisanje kategorija (terms) <code>novo-u-web-shopu</code> 20 dana nakon objave.<br>Hook: <code>haddad_remove_term</code>, <code>wp</code>.' );
$doc_html .= ihwp_docdiv( 'scope', 'p', 'Global' );
$doc_html .= ihwp_contain_end();

// PROIZVODI IZ NARUDŽBI.
$doc_html .= ihwp_contain_st();
$doc_html .= ihwp_docdiv( 'title', 'h4', 'Proizvodi iz narudžbi' );
$doc_html .= ihwp_docdiv( 'desc', 'p', 'Izlistavanje proizvoda po narudžbama. Proizvodi iz iste narudžbe su grupirani označeni istom bojom.<br>Hook: <code>admin_menu</code> - funkcija <code>haddad_admin_wc_order_products</code><br>Class datoteka: <code>includes/class-image-haddad-order-products.php</code> ' );
$doc_html .= ihwp_docdiv( 'scope', 'p', 'Admin' );
$doc_html .= ihwp_contain_end();

// FILTRIRANJE NARUDŽBI.
$doc_html .= ihwp_contain_st();
$doc_html .= ihwp_docdiv( 'title', 'h4', 'Filtiranje narudžbi po metodi plaćanja' );
$doc_html .= ihwp_docdiv( 'desc', 'p', 'Dodatni dropdown filter na admin ispisu narudžbi za filtriranje po metodi plaćanja.<br>Hook:<code>admin_init</code>, funkcija <code>haddad_wc_filter_orders_by_payment</code><br>' );
$doc_html .= ihwp_docdiv( 'scope', 'p', 'Admin' );
$doc_html .= ihwp_contain_end();

// PLUGIN : Print Invoice & Delivery Notes for WooCommerce.
$doc_html .= ihwp_contain_st();
$doc_html .= ihwp_docdiv( 'title', 'h4', 'PLUGIN: Print Invoice & Delivery Notes for WooCommerce<br><small>Neaktivno</small>' );
$doc_html .= ihwp_docdiv( 'desc', 'p', 'Dodavanje polja za printanje sa kupon kodom i slikom proizvoda.<br>Hook:<code>wcdn_order_info_fields</code>, funkcija <code>haddad_custom_orderprint_fields</code><br>Hook:<code>wcdn_order_item_before</code>, funkcija <code>haddad_custom_orderprint_product_image</code><br>' );
$doc_html .= ihwp_docdiv( 'scope', 'p', 'Admin' );
$doc_html .= ihwp_contain_end();

// WP-CLI bulk brisanje proizvoda.
$doc_html .= ihwp_contain_st();
$doc_html .= ihwp_docdiv( 'title', 'h4', 'WP-CLI Bulk brisanje proizvoda<br><small>Developer</small>' );
$doc_html .= ihwp_docdiv( 'desc', 'p', 'Custom WP-CLI komanda za bulk brisanje postova, proizvoda (bilo koji post type), stariji od zadanog datuma. Parametri: POST TYPE, POST STATUS, GODINA, MJESEC I DAN su obavezni (po tom redosljedu).<hr>Sintaksa komande je: <code>wp delete-before [post_type] [post_status] [godina] [mjesec] [dan] [--number=100] [--date=post_date]</code>.' );
$doc_html .= ihwp_docdiv( 'scope', 'p', 'Admin / Dev' );
$doc_html .= ihwp_contain_end();

// WP-CLI OPTIMIZIRANJE BAZE.
$doc_html .= ihwp_contain_st();
$doc_html .= ihwp_docdiv( 'title', 'h4', 'WP-CLI optimizacija baze<br><small>Developer</small>' );
$doc_html .= ihwp_docdiv( 'desc', 'p', 'Custom WP-CLI komanda za optimizaciju baze podataka.<br><strong>VAŽNO: OBAVEZNO NAPRAVITI BACKUP BAZE PODATAKA PRIJE UPOTREBE OVE KOMANDE.</strong><hr>Sintaksa komande je: <code>wp haddad-optimize-db</code>. Nema dodatnih parametara.' );
$doc_html .= ihwp_docdiv( 'scope', 'p', 'Admin / Dev' );
$doc_html .= ihwp_contain_end();

// LOG PROMJENA STATUSA PROIZVODA.
$doc_html .= ihwp_contain_st();
$doc_html .= ihwp_docdiv( 'title', 'h4', 'Log zapisi promjena na proizvodima<br><small>Developer</small>' );
$doc_html .= ihwp_docdiv( 'desc', 'p', 'Serverski zapis promjene proizvoda. Zapisuje promjene samo ako se status proizvoda mijenja iz Skice (draft) u Objavljeno (publish)<hr>Zapisivanje u JSON datoteku (/wp-content/uploads/haddad_product_statuses/log.json) <br>Hook: <code>wp_insert_post_data</code> - Funkcija: <code>haddad_product_status_update_log_json</code>.<hr><strong style="color:red"><code>BUG ALERT!</code> - greška u kodu - opcija je trenutno onemogućena.</strong><br>Zapisivanje u bazu podataka<br>Funkcije <code>haddad_product_status_update_log_db</code>, <code>haddad_remove_log_db_trigger</code><br>Datoteke: class-image-haddad-wp-admin.php, class-image-haddad-wp.php, log_products_draft_publish_json.php, log_products_draft_publish_db.php ' );
$doc_html .= ihwp_docdiv( 'scope', 'p', 'Admin / Dev' );
$doc_html .= ihwp_contain_end();

// STRANICA SINGLE PROIZVODA.
$doc_html .= ihwp_contain_st();
$doc_html .= ihwp_docdiv( 'title', 'h4', 'Stranica pojedinačnog proizvoda' );
$doc_html .= ihwp_docdiv(
	'desc',
	'p',
	'<strong>Custom sadržaj na stranici pojedinog proizvoda.</strong><br>Hook: <code>woocommerce_single_product_summary</code> - Funkcije: <code>sp_custom_html</code>, <code>sp_custom_html_2</code>.<br>Postavke - tab "WooCommerce ostalo"<hr>' .
	'<strong>Dodatna custom oznaka "Univerzalna veličina"</strong>. <br>Hook: <code>woocommerce_single_product_summary</code> - Funkcija: <code>haddad_universal_size_attribute</code><hr>' .
	'<strong>Reorganizirani tabovi na pojedinačnom proizvodu.</strong><br>Hook: <code>woocommerce_product_tabs</code> - Funkcija: <code>haddad_reorder_tabs</code><hr>'.
	'<strong>Custom naslovi za "Related products" i "Upsale products".</strong><br>Hook: <code>woocommerce_product_upsells_products_heading</code> - Funkcija: <code>haddad_upsells_heading</code><br>Hook: <code>woocommerce_product_related_products_heading</code> - Funkcija: <code>haddad_related_heading</code><hr>'. 
	'<strong>Isključivanje povezanih (Upsell) proizvoda,</strong><br>opcija se uklj/isklj. na tabu "WooCommerce ostalo", sekcija "Stranica pojedinačnog proizvoda - postavke"<br>Hookovi: <code>wp</code>, <code>woocommerce_after_single_product_summary</code> - Funkcija <code>haddad_remove_upsells</code><hr>' .
	'<strong>Izuzimanje proizvoda van zalihe iz related ("Možda će vam se takodjer ...") proizvoda.</strong><br>Hook: <code>woocommerce_related_products</code> - Funkcija: <code>haddad_no_out_of_stock_in_related</code><hr>' .
	'<strong>Prikaz stanja u skladištu, putem eksternog ERP-a.</strong><br>Ajax funkcije za varijabilne proizvode.<br>Hook: <code>woocommerce_single_product_summary</code> - Funkcija: <code>init_stanje_u_skladistima</code><br>Hook: <code>wp_ajax_nopriv_stanje_u_skladistima</code> <code>wp_ajax_stanje_u_skladistima</code> - Funkcija: <code>stanje_u_skladistima</code>'
);
$doc_html .= ihwp_docdiv( 'scope', 'p', 'Frontend' );
$doc_html .= ihwp_contain_end();

// SAMO OBJAVLJENI PROIZVODI.
$doc_html .= ihwp_contain_st();
$doc_html .= ihwp_docdiv( 'title', 'h4', 'Samo objavljeni proizvodi' );
$doc_html .= ihwp_docdiv( 'desc', 'p', 'Forsiranje prikaza samo objavljenih proizvoda (koji nemaju status skice)<br>Hook: <code>woocommerce_product_query</code> - Funkcija: <code>haddad_no_drafts_in_wc_loop</code>.' );
$doc_html .= ihwp_docdiv( 'scope', 'p', 'Frontend' );
$doc_html .= ihwp_contain_end();

// PRIKAZ KUPONSKE CIJENE NA SUBTOTAL PROIZVODA U KOŠARICI.
$doc_html .= ihwp_contain_st();
$doc_html .= ihwp_docdiv( 'title', 'h4', 'Kuponska cijena u košarici' );
$doc_html .= ihwp_docdiv( 'desc', 'p', 'Prikaz kuponske (snižene) cijene ispod originalne u košarici, po pojedinom proizvodu.<br>Hook: <code>woocommerce_cart_item_subtotal</code> - Funkcija: <code>haddad_cart_item_coupon_subtotal</code>.' );
$doc_html .= ihwp_docdiv( 'scope', 'p', 'Frontend' );
$doc_html .= ihwp_contain_end();

// PRIKAZ KUPONSKE CIJENE U KATALOGU/KATEGORIJAMA.
$doc_html .= ihwp_contain_st();
$doc_html .= ihwp_docdiv( 'title', 'h4', 'Kuponska cijena u katalogu/kategorijama (loop)' );
$doc_html .= ihwp_docdiv( 'desc', 'p', 'Prikaz kuponske (snižene) cijene ispod originalne u katalogu/kategorijama, po pojedinom proizvodu. Primjenjuje se svugdje gdje se koristi listanje proizvoda u loopu.<br>Hook: <code>woocommerce_get_price_html</code> - Funkcija: <code>haddad_coupon_after_price</code>.' );
$doc_html .= ihwp_docdiv( 'scope', 'p', 'Frontend' );
$doc_html .= ihwp_contain_end();

// OVERRIDE WC PREDLOŽAKA.
$doc_html .= ihwp_contain_st();
$doc_html .= ihwp_docdiv( 'title', 'h4', 'Prilagođeni WC predlošci' );
$doc_html .= ihwp_docdiv( 'desc', 'p', 'Prilagođeni WC templatei, koji imaju prednost pred WC i predlošcima teme (ili tema).<br>Hook: <code>wc_get_template</code> - Funkcija: <code>ihwp_wc_templates</code>.<hr>Predlošci se nalaze u "public/woocommerce/" folderu.' );
$doc_html .= ihwp_docdiv( 'scope', 'p', 'Frontend' );
$doc_html .= ihwp_contain_end();

// PRIKAZ KUPONSKE CIJENE U KATALOGU/KATEGORIJAMA.
$doc_html .= ihwp_contain_st();
$doc_html .= ihwp_docdiv( 'title', 'h4', 'Prilagođeni cross-sell proizvodi u košarici' );
$doc_html .= ihwp_docdiv( 'desc', 'p', 'Prilagođeni prikaz cross-sell proizvoda po ključu odabira proizvoda iz kategorija kojima pripadaju proizvodi u košarici, ako nema ručno izabranih proizvoda u adminu pojedinih proizvoda.<br>Hook: <code>haddad_cross_sells</code> - Funkcija: <code>haddad_get_related_to_cart</code>.<hr>Koristi override original predloška (ili predloška iz teme) sa predloškom u "public/woocommerce/cart/cross-sells.php" datoteci.' );
$doc_html .= ihwp_docdiv( 'scope', 'p', 'Frontend' );
$doc_html .= ihwp_contain_end();

// FLATSOME i HAUMEA CHILD TEME.
$doc_html .= ihwp_contain_st();
$doc_html .= ihwp_docdiv( 'title', 'h4', 'Prilagode Flatsome teme<br><small>Podrška i za Haddad 2018 (Haumea Child) temu.</small>' );
$doc_html .= ihwp_docdiv(
	'desc',
	'p',
	'Prilagodbe i dodavanje funkcija Flatsome temi.<hr>'.
	'Kontrola cross-sells proizvoda (košarica)<br>Hook: <code>woocommerce_cross_sells_total</code> - Funkcija: <code>flatsome_change_cross_sells_product_no</code>.<br>Hook: <code>woocommerce_cross_sells_columns</code> - Funkcija: <code>flatsome_change_cross_sells_columns</code>.<hr>'.
	'Dodavanje badgea "Novo" i loga Fabriq/Image Haddad slikama proizvoda.<br>Hookovi: <code>flatsome_woocommerce_shop_loop_images</code>, <code>woocommerce_single_product_summary</code> - Funkcije: <code>haddad_new_product</code> i <code>haddad_fabriq_logo</code><hr>' .
	'(Single proizvod) Prilagođen "Add to wishlist" gumb (YITH plugin - treba isključiti prikaz gumba u opcijama plugina)<br>Hookovi <code>woocommerce_before_single_product</code> <code>woocommerce_after_single_product_summary</code> <code>woocommerce_after_add_to_cart_button</code> (remove YITH default action)<br>Funkcije: <code>start_product_summary</code>; <code>end_product_summary</code>; <code>flatsome_wishlist_icon</code>; <code>flatsome_wishlist_icon_css</code>'
);
$doc_html .= ihwp_docdiv( 'scope', 'p', 'Frontend' );
$doc_html .= ihwp_contain_end();

// PRAZNA KOŠARICA.
$doc_html .= ihwp_contain_st();
$doc_html .= ihwp_docdiv( 'title', 'h4', 'Stranica prazne košarice' );
$doc_html .= ihwp_docdiv( 'desc', 'p', 'Prilagodba stranice prazne košarice - dodatna ponuda proizvoda sa sniženim cijenama ispod poruke o praznoj košarici.<br>Hook: <code>woocommerce_cart_is_empty</code> - Funkcija: <code>haddad_empty_cart_sale_loop</code>' );
$doc_html .= ihwp_docdiv( 'scope', 'p', 'Frontend' );
$doc_html .= ihwp_contain_end();

// CHECKOUT STRANICA.
$doc_html .= ihwp_contain_st();
$doc_html .= ihwp_docdiv( 'title', 'h4', 'Stranica plaćanja (checkout)' );
$doc_html .= ihwp_docdiv( 'desc', 'p', 'Onemogućivanje form polja za upis kupon koda na stranici plaćanja.<br>Hook: <code>woocommerce_before_checkout_form</code> - Funkcija: <code>haddad_remove_checkout_coupon_form</code><hr>Dodatni red u checkout tabeli za prikaz ukupne cijene, smanjene za iznose kupona, prije dodavanja troškova dostave.<br>Hook: <code>haddad_coupon_subtotal</code> - Funkcija: <code>haddad_coupons_applied_to_subtotal</code> - Hook se nalazi u WC predlošku ovog plugina - "public/woocommerce/checkout/review-order.php".' );
$doc_html .= ihwp_docdiv( 'scope', 'p', 'Frontend' );
$doc_html .= ihwp_contain_end();

// THANK YOU STRANICA.
$doc_html .= ihwp_contain_st();
$doc_html .= ihwp_docdiv( 'title', 'h4', 'Prilagođeni tekst na stranici plaćanja' );
$doc_html .= ihwp_docdiv( 'desc', 'p', 'Prilagođeni tekst se može mijenjati u postavkama ovog plugina, tab "WooCommerce ostalo".<br>Hook: <code>woocommerce_thankyou_order_received_text</code> - Funkcija: <code>change_order_received_text</code>' );
$doc_html .= ihwp_docdiv( 'scope', 'p', 'Frontend' );
$doc_html .= ihwp_contain_end();

// T&C PDF ZA EMAILOVE.
$doc_html .= ihwp_contain_st();
$doc_html .= ihwp_docdiv( 'title', 'h4', 'PDF attachment emailova' );
$doc_html .= ihwp_docdiv( 'desc', 'p', 'Dodavanje PDF attachmenta sa općim uvjetima emailovima narudžbi.<br>Hook: <code>woocommerce_email_attachments</code> - Funkcija: <code>haddad_attach_terms_conditions_pdf_to_email</code><hr>Koristi i custom funckiju <code>wp_get_attachment_by_post_name</code> za attach PDF sa imenom datoteke.' );
$doc_html .= ihwp_docdiv( 'scope', 'p', 'Admin' );
$doc_html .= ihwp_contain_end();

// CUSTOM TEXT ZA OTKAZANU NARUDŽBU.
$doc_html .= ihwp_contain_st();
$doc_html .= ihwp_docdiv( 'title', 'h4', 'Prilagođeni tekst otkazane narudžbe' );
$doc_html .= ihwp_docdiv( 'desc', 'p', 'Hook: <code>woocommerce_order_status_cancelled</code> - Funkcija: <code>haddad_notification_status_canceled</code>' );
$doc_html .= ihwp_docdiv( 'scope', 'p', 'Admin' );
$doc_html .= ihwp_contain_end();

// ONEMOGUĆAVANJE REVIEWOVA.
$doc_html .= ihwp_contain_st();
$doc_html .= ihwp_docdiv( 'title', 'h4', 'Onemogućavanje recenzija proizvoda' );
$doc_html .= ihwp_docdiv( 'desc', 'p', 'Onemogućavanje recenzija se može uključiti/isključiti u postavkama - tab "WooCommerce osnovno" <br>Hook: <code>init</code> - Funkcija: <code>haddad_remove_reviews</code>' );
$doc_html .= ihwp_docdiv( 'scope', 'p', 'Global' );
$doc_html .= ihwp_contain_end();

// ISKLUČIVANJE POJEDINIH KATEGORIJA IZ WIDGETA.
$doc_html .= ihwp_contain_st();
$doc_html .= ihwp_docdiv( 'title', 'h4', 'Isključivanje kategorija u widgetu' );
$doc_html .= ihwp_docdiv( 'desc', 'p', 'Kategorije za isključivanje iz widgeta "Product categories" se mogu dodati u postavkama - tab "WooCommerce ostalo" <br>Hook: <code>woocommerce_product_categories_widget_args</code> - Funkcija: <code>haddad_exclude_product_cat_widget</code>' );
$doc_html .= ihwp_docdiv( 'scope', 'p', 'Frontend' );
$doc_html .= ihwp_contain_end();

// PLUGIN: WOOCOMMERCE TABLE RATE SHIPPING
$doc_html .= ihwp_contain_st();
$doc_html .= ihwp_docdiv( 'title', 'h4', 'Limiti dostave nakon kupona.<br><small>PLUGIN: WooCommerce Table Rate Shipping</small>' );
$doc_html .= ihwp_docdiv( 'desc', 'p', 'Primjena limita ordeđenih u postavkama table rate shippinga (WooCommerce > Postavke > Dostava) nakon primjene kupona. <br>Hook: <code>woocommerce_table_rate_compare_price_limits_after_discounts</code> - Funkcija: <code>__return_true</code>' );
$doc_html .= ihwp_docdiv( 'scope', 'p', 'Global' );
$doc_html .= ihwp_contain_end();

// GOOGLE TAG / ANALYTICS.
$doc_html .= ihwp_contain_st();
$doc_html .= ihwp_docdiv( 'title', 'h4', 'Google Tag / Analytics' );
$doc_html .= ihwp_docdiv( 'desc', 'p', 'Dodavanje script i html tagova u kod stranice za potrebe Google Tag i Google Analytics.. <br>Hookovi: <code>wp_head</code>, <code>wp_body_open</code> - Funkcije: <code>haddad_ga_gtagjs</code>, <code>haddad_gtm</code>, <code>haddad_gtm_iframe</code>' );
$doc_html .= ihwp_docdiv( 'scope', 'p', 'Frontend' );
$doc_html .= ihwp_contain_end();

// WPSEO_ROBOTS.
$doc_html .= ihwp_contain_st();
$doc_html .= ihwp_docdiv( 'title', 'h4', 'Pristup SEO robotima samo za HR stranice<br><small>PLUGIN: Yoast SEO</small>' );
$doc_html .= ihwp_docdiv( 'desc', 'p', 'Osiguranje (privremeno) da SEO roboti neće eventualno slati krivu informaciju o EN ili eventualnim drugim jezičnim verzijama stranice. Isključiti kod uspostave višejezičnih stranica - postavka u tabu "Druge postavke". <br>Hook: <code>wpseo_robots</code> - Funkcija: <code>haddad_hide_en_pages</code>' );
$doc_html .= ihwp_docdiv( 'scope', 'p', 'Frontend' );
$doc_html .= ihwp_contain_end();

// KIRKI.
$doc_html .= ihwp_contain_st();
$doc_html .= ihwp_docdiv( 'title', 'h4', 'Isključivanje telemetrije<br><small>PLUGIN: Kirki / Flatsome Kirki library</small>' );
$doc_html .= ihwp_docdiv( 'desc', 'p', 'Hook: <code>kirki_telemetry</code> - Funkcija: <code>__return_false</code>' );
$doc_html .= ihwp_docdiv( 'scope', 'p', 'Admin' );
$doc_html .= ihwp_contain_end();

// BEZ LAZY LOADINGA.
$doc_html .= ihwp_contain_st();
$doc_html .= ihwp_docdiv( 'title', 'h4', 'Isključivanje Lazy Loadinga na početnoj stranici' );
$doc_html .= ihwp_docdiv( 'desc', 'p', 'Zbog problema sa prikazom slika u različitim Elementor plugin sekcijama, isključen je Lazy load slika, samo na homepageu. Hook: <code>wp_lazy_loading_enabled</code> - Funkcija: <code>no_lazy_load_on_homepage</code>' );
$doc_html .= ihwp_docdiv( 'scope', 'p', 'Frontend' );
$doc_html .= ihwp_contain_end();

// BEZ KOMENTARA.
$doc_html .= ihwp_contain_st();
$doc_html .= ihwp_docdiv( 'title', 'h4', 'Isključivanje komentara globalno' );
$doc_html .= ihwp_docdiv( 'desc', 'p', 'Zbog nekorištenja WP komentara, isključeni su i na frontendu i adminu. Postavka se može iskljčiti na tabu "Druge postavke". Funkcija: <code>remove_comments</code> u "public/class-image-haddad-wp-public.php"' );
$doc_html .= ihwp_docdiv( 'scope', 'p', 'Global' );
$doc_html .= ihwp_contain_end();

// PRILAGODBA BOJA ZA CIJENU SA POPUSTOM.
$doc_html .= ihwp_contain_st();
$doc_html .= ihwp_docdiv( 'title', 'h4', 'Boje za cijene sa popustom' );
$doc_html .= ihwp_docdiv( 'desc', 'p', 'Mogućnost prilagodbe boja pozadine i fonta za cijenu sa popustom. Primijenjuje se na katalogu i pojedinačnom proizvodu.<br>Hook: <code>wp_enqueue_scripts</code> - Funkcija: <code>sale_price_styles</code> u "public/class-image-haddad-wp-public.php"' );
$doc_html .= ihwp_docdiv( 'scope', 'p', 'Frontend' );
$doc_html .= ihwp_contain_end();
