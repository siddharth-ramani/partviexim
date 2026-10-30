<?php
function seo_page_title(string $slug, string $title): string {
    $defaults = [
        'index' => ['Home', 'Indian Ingredients & Agricultural Products Exporter'],
        'about' => ['About us', 'About Our Agricultural Export Business'],
        'products' => ['Our Products', 'Dehydrated Onion, Garlic, Peanuts & Indian Ingredients'],
        'certificates' => ['Certificates', 'FSSAI License, GST, IEC & Udyam Certificates'],
        'contact' => ['Contact us', 'Contact Us for Agricultural Export Inquiries'],
    ];
    return isset($defaults[$slug]) && $title === $defaults[$slug][0] ? $defaults[$slug][1] : $title;
}
