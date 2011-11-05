Options +FollowSymlinks
RewriteEngine on

RewriteBase /

RewriteCond %{REQUEST_URI} !^{{ subdir }}/gallery_engine(.*)$
RewriteCond %{REQUEST_URI} !^{{ subdir }}/index(.*)$
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^(.*)$ {{ subdir }}/index.php?request=$1 [L]