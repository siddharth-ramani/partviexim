<?php
declare(strict_types=1);

function seo_base(): string {
    $base=rtrim(getenv('PARTHVI_SITE_URL') ?: 'https://parthviexim.com','/');
    $parts=parse_url($base);
    if(!$parts||!in_array(strtolower($parts['scheme']??''),['http','https'],true)||empty($parts['host'])||isset($parts['query'])||isset($parts['fragment'])||isset($parts['user'])||isset($parts['pass']))throw new RuntimeException('PARTHVI_SITE_URL must be an absolute website URL without credentials, query or fragment.');
    return $base.'/';
}
function seo_public_paths(): array { return ['', 'about','products','certificates','contact','privacy-policy','terms-and-conditions']; }
function seo_is_preview(): bool {
    $host=strtolower(parse_url('http://'.($_SERVER['HTTP_HOST']??''),PHP_URL_HOST)??'');
    $canonical=strtolower(parse_url(seo_base(),PHP_URL_HOST)??'');
    return $host===''||$host!==$canonical||$host==='localhost'||str_ends_with($host,'.localhost')||filter_var($host,FILTER_VALIDATE_IP)!==false;
}
function seo_modified(array ...$records): ?string {
    $times=[];foreach($records as $record){$value=$record['_updated_at']??null;if(is_string($value)){try{$date=new DateTimeImmutable($value);if($date->getTimestamp()<=time())$times[]=$date->getTimestamp();}catch(Throwable $e){}}}
    return $times?gmdate('Y-m-d\TH:i:s\Z',max($times)):null;
}
