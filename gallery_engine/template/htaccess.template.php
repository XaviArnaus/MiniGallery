Options +FollowSymlinks
RewriteEngine on

RewriteBase /

# In this rules we assume that the 'index.php' script 
# and the rest of the engine is placed inside
# http://example.com/Fotos/
#
# If the script is placed in a root folder,
# just strip /Fotos everywhere.
#
# The script proposes you a .htaccess content if you
# start without having one.
#
RewriteCond %{REQUEST_URI} !^{{ subdir }}/gallery_engine(.*)$
RewriteCond %{REQUEST_URI} !^{{ subdir }}/index(.*)$
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^(.*)$ {{ subdir }}/index.php?request=$1 [L]