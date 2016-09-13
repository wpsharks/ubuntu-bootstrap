<?php
namespace WebSharks\Ubuntu\Bootstrap;

$cloudflare_ips_v4 = file_get_contents('https://www.cloudflare.com/ips-v4');
$cloudflare_ips_v6 = file_get_contents('https://www.cloudflare.com/ips-v6');

# CloudFlare is known to block requests now & again.
# Check for `<` to indicate an HTML error; i.e., 403, etc.

if ($cloudflare_ips_v4 && mb_strpos($cloudflare_ips_v4, '<') === false) {
    file_put_contents(__DIR__.'/ips-v4', $cloudflare_ips_v4);
}
if ($cloudflare_ips_v6 && mb_strpos($cloudflare_ips_v6, '<') === false) {
    file_put_contents(__DIR__.'/ips-v6', $cloudflare_ips_v6);
}
