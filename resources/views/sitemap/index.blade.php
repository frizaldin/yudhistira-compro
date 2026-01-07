<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  @foreach ($utama as $u)
  <url>
    <loc>{{url($u)}}</loc>
    <lastmod>{{date('Y-m-d')}}</lastmod>
    <priority>1.00</priority>
  </url>
  @endforeach
  
  @foreach ($blog as $key=>$c)
    <url>
      <loc>{{ $c->urls }}</loc>
      <lastmod>{{date('Y-m-d', strtotime($c->created_at))}}</lastmod>
      <priority>{{$key < 1 ? '1.00' : '0.80'}}</priority>
    </url>
  @endforeach
  @foreach ($portfolio as $key=>$c)
    <url>
      <loc>{{ $c->urls }}</loc>
      <lastmod>{{date('Y-m-d', strtotime($c->created_at))}}</lastmod>
      <priority>{{$key < 1 ? '1.00' : '0.80'}}</priority>
    </url>
  @endforeach
  @foreach ($service as $key=>$c)
    <url>
      <loc>{{ $c->urls }}</loc>
      <lastmod>{{date('Y-m-d', strtotime($c->created_at))}}</lastmod>
      <priority>{{$key < 1 ? '1.00' : '0.80'}}</priority>
    </url>
  @endforeach
</urlset>