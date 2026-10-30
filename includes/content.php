<?php
// Structured, plain-text website content. Values remain safe to edit without HTML.
function content_schema(): array {
    static $schema;
    return $schema ??= json_decode(file_get_contents(__DIR__.'/content-schema.json'), true, 512, JSON_THROW_ON_ERROR);
}
function content_value(string $key, ?string $page=null): string {
    global $currentPage;
    $page ??= $currentPage['slug'] ?? 'site';
    static $rows=[];
    if (!isset($rows[$page])) $rows[$page]=cms_page($page)['data']['fields'] ?? [];
    return (string)($rows[$page][$key]['value'] ?? content_schema()[$page][$key]['value'] ?? '');
}
function content_text(string $key, ?string $page=null): string { return e(content_value($key,$page)); }
function content_on(string $key, ?string $page=null): bool { return content_value($key,$page)==='1'; }
function content_image(string $key, ?string $page=null): string { return e(media_url(content_value($key,$page))); }
function content_lines(string $key, ?string $page=null): string { return nl2br(content_text($key,$page)); }
function content_categories(array $products): array {
    $result=[];
    foreach(cms_records('categories') as $r){$d=$r['data'];$items=array_values(array_filter($products,fn($p)=>$p['category']===$d['name']));if($items)$result[$d['name']]=['data'=>$d,'products'=>$items];}
    return $result;
}
function content_valid_link(string $value): bool {
    if($value==='')return false;
    if(preg_match('~^https?://~i',$value))return (bool)filter_var($value,FILTER_VALIDATE_URL);
    return (bool)preg_match('~^(?:\./|/)?[a-zA-Z0-9][a-zA-Z0-9/_?&=.%+#-]*$~',$value) && !str_contains($value,'..') && !str_starts_with($value,'//');
}
function content_visible_products(array $products): array {
    $categories=cms_records('categories',false);
    if(!$categories)return $products;
    $names=array_column(array_column(array_filter($categories,fn($r)=>(bool)$r['published']),'data'),'name');
    return array_values(array_filter($products,fn($p)=>in_array($p['category'],$names,true)));
}
function content_format(string $key,array $values,?string $page=null): string {
    $tokens=[];foreach($values as $name=>$value)$tokens['{'.$name.'}']=(string)$value;
    return e(strtr(content_value($key,$page),$tokens));
}
function content_whatsapp_link(string $product): string {
    $url=setting('whatsapp');$parts=explode('?',explode('#',$url,2)[0],2);$query=[];
    if(isset($parts[1]))parse_str($parts[1],$query);
    $query['text']=strtr(content_value('whatsapp_message','products'),['{product}'=>$product]);
    return $parts[0].'?'.http_build_query($query,'','&',PHP_QUERY_RFC3986);
}
function cms_slug(string $name): string {
    $ascii=iconv('UTF-8','ASCII//TRANSLIT//IGNORE',$name);
    return trim(preg_replace('/[^a-z0-9]+/','-',strtolower($ascii?:$name)),'-');
}
function category_url(string $name): string {
    global $productCategories;
    $category=$productCategories[$name]??null;
    if($category){if(count($category['products'])===1)return 'products/'.$category['products'][0]['slug'];if(!empty($category['data']['slug']))return 'categories/'.$category['data']['slug'];}
    return 'products?category='.rawurlencode($name).'#catalogue';
}
function cms_unique_category_slug(string $name,int $ignore=0): string {
    $base=cms_slug($name);if($base==='')throw new RuntimeException('Enter a category URL using English letters, numbers and hyphens.');
    $used=[];foreach(cms_records('categories',false) as $r)if((int)$r['id']!==$ignore){$used[]=$r['data']['slug']??'';$used=array_merge($used,$r['data']['previous_slugs']??[]);}
    $slug=$base;$suffix=2;while(in_array($slug,$used,true))$slug=$base.'-'.$suffix++;
    return $slug;
}
function content_has_custom_theme(): bool {
    foreach(['theme_primary'=>'#173e32','theme_accent'=>'#80572d','theme_background'=>'#fdfcf8'] as $key=>$value)if(strtolower(content_value($key,'site'))!==$value)return true;
    return false;
}
