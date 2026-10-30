"""Read-only SEO checks against local Apache, including .htaccess redirects."""
import argparse,json,re,urllib.request,urllib.error,urllib.parse,xml.etree.ElementTree as ET
from html.parser import HTMLParser
parser=argparse.ArgumentParser();parser.add_argument('--base',default='http://localhost/parthviexim/');args=parser.parse_args();base=args.base.rstrip('/')+'/'
class NoRedirect(urllib.request.HTTPRedirectHandler):
 def redirect_request(self,*args,**kwargs):return None
opener=urllib.request.build_opener(NoRedirect)
def fetch(path='',production=False):
 headers={'Host':'parthviexim.com','X-Forwarded-Proto':'https'} if production else {}
 try:r=opener.open(urllib.request.Request(base+path,headers=headers),timeout=20)
 except urllib.error.HTTPError as e:r=e
 return r.status,dict((k.lower(),v) for k,v in r.headers.items()),r.read().decode('utf-8','replace')
class Head(HTMLParser):
 def __init__(self):super().__init__();self.meta={};self.canonical=[];self.scripts=[];self.json=False;self.title='';self.intitle=False
 def handle_starttag(self,t,attrs):
  d=dict(attrs)
  if t=='meta':self.meta[d.get('name',d.get('property',''))]=d.get('content','')
  if t=='link' and d.get('rel')=='canonical':self.canonical.append(d['href'])
  if t=='script' and d.get('type')=='application/ld+json':self.json=True
  if t=='title':self.intitle=True
 def handle_endtag(self,t):
  if t=='script':self.json=False
  if t=='title':self.intitle=False
 def handle_data(self,text):
  if self.json:self.scripts.append(json.loads(text))
  if self.intitle:self.title+=text
checks=0
def check(ok,msg):
 global checks
 if not ok:raise AssertionError(msg)
 checks+=1
status,headers,xml=fetch('sitemap.xml',True);check(status==200 and 'application/xml' in headers.get('content-type',''),'XML sitemap response')
ns={'s':'http://www.sitemaps.org/schemas/sitemap/0.9','image':'http://www.google.com/schemas/sitemap-image/1.1'};root=ET.fromstring(xml);urls=[x.text for x in root.findall('s:url/s:loc',ns)]
check(len(urls)==44 and len(set(urls))==44,'Seven pages, six category landing pages and 31 unique products')
check(all(u.startswith('https://parthviexim.com/') and not any(c in u for c in ['?','#']) and not u.endswith('.php') for u in urls),'Only clean canonical URLs')
titles=set();descriptions=set()
for url in urls:
 path=urllib.parse.urlparse(url).path.lstrip('/');status,h,body=fetch(path,True);head=Head();head.feed(body)
 check(status==200,url+' is available');check(head.canonical==[url],url+' has one matching canonical')
 check(head.meta.get('robots')=='index, follow, max-image-preview:large' and 'noindex' not in h.get('x-robots-tag',''),url+' is indexable on canonical host')
 check(bool(head.title.strip()) and head.title not in titles,url+' has a unique title');titles.add(head.title)
 check(bool(head.meta.get('description','').strip()) and head.meta['description'] not in descriptions,url+' has a unique description');descriptions.add(head.meta['description'])
 check(head.meta.get('og:url')==url and head.meta.get('og:title')==head.title and head.meta.get('twitter:title')==head.title,url+' social/canonical metadata match')
 check(int(head.meta.get('og:image:width','0'))>0 and int(head.meta.get('og:image:height','0'))>0 and bool(head.meta.get('twitter:image:alt')),url+' image dimensions and alt text')
 graph=head.scripts[0]['@graph'];types=[n['@type'] for n in graph];check('Organization' in types and 'WebSite' in types,url+' has valid JSON-LD graph')
 if path.startswith('products/'):
  check('BreadcrumbList' in types and 'Product' in types,url+' product/breadcrumb data')
  product=next(n for n in graph if n['@type']=='Product');check(not any(k in product for k in ['offers','review','aggregateRating']),url+' contains no invented prices or ratings')
 else:
  check(path=='' or 'BreadcrumbList' in types,url+' breadcrumb data')
  if path.startswith('categories/'):
   check('CollectionPage' in types,url+' category collection schema')
   collection=next(n for n in graph if n['@type']=='CollectionPage');check(len(collection['mainEntity']['itemListElement'])>=2,url+' has a meaningful product range')
 preview_status,preview_h,preview_body=fetch(path);preview=Head();preview.feed(preview_body);check(preview_status==200 and 'noindex' in preview.meta.get('robots','') and 'noindex' in preview_h.get('x-robots-tag',''),url+' preview is noindex')
for node in root.findall('s:url/image:image/image:loc',ns):
 status,h,_=fetch(urllib.parse.urlparse(node.text).path.lstrip('/'),True);check(status==200 and h.get('content-type','').startswith('image/'),'Sitemap image is accessible')
status,h,robots=fetch('robots.txt',True);check(status==200 and 'text/plain' in h.get('content-type',''),'Production robots content type')
check('Disallow: /\n' not in robots and 'Allow: /assets/' in robots,'Production robots allows public rendering resources')
for private in ['admin','includes','config','database','scripts','tests']:check('Disallow: /'+private+'/' in robots,'Robots excludes '+private)
check('Sitemap: https://parthviexim.com/sitemap.xml' in robots,'Robots points to canonical sitemap');check(fetch('robots.txt')[2]=='User-agent: *\nDisallow: /\n','Preview robots blocks crawling')
for path,target in [('index.php','/parthviexim/'),('about.php','/parthviexim/about'),('about/','/parthviexim/about'),('products/white-onion-flakes/','/parthviexim/products/white-onion-flakes'),('product.php?slug=white-onion-flakes','/parthviexim/products/white-onion-flakes'),('robots.php','/parthviexim/robots.txt'),('sitemap.php','/parthviexim/sitemap.xml')]:
 status,h,_=fetch(path);check(status==301 and urllib.parse.urlparse(h.get('location','')).path==target,'Canonical redirect '+path)
for path in ['unknown-page','missing/deep/path','products/unavailable-product']:
 status,h,body=fetch(path,True);head=Head();head.feed(body);check(status==404 and 'noindex' in h.get('x-robots-tag','') and 'noindex' in head.meta.get('robots',''),'Proper noindex 404: '+path);check(not head.canonical and not head.scripts,'404 has no misleading canonical or structured entity')
for path in ['ADMIN-GUIDE.md','includes/content-schema.json','database/schema.sql','config/database.php']:check(fetch(path)[0]==403,'Private resource not exposed: '+path)
status,h,body=fetch('admin/login.php',True);check(status==200 and 'noindex' in h.get('x-robots-tag','') and 'noindex,nofollow' in body,'Admin noindex header and metadata')
print(checks,'SEO crawl checks passed across',len(urls),'public URLs.')
