vcl 4.0;
import std;
import directors;

backend default {
  .host = "nginx";
  .port = "81";
}

sub vcl_recv {

# Normalize the header, remove the port (in case you're testing this on various TCP ports)
  set req.http.Host = regsub(req.http.Host, ":[0-9]+", "");

  # set or append the client.ip to X-Forwarded-For header. Important for logging and correct IPs.
  if (req.restarts == 0) {
    if (req.http.X-Forwarded-For) {
      set req.http.X-Forwarded-For = req.http.X-Forwarded-For + ", " + client.ip;
    } else {
      set req.http.X-Forwarded-For = client.ip;
    }
  }

###
### Do not Cache: special cases
###

  # Do not cache AJAX requests.
    if (req.http.X-Requested-With == "XMLHttpRequest") {
        return(pass);
    }

  # Post requests will not be cached
    if (req.http.Authorization || req.method == "POST") {
        return (pass);
    }

  # Only cache GET or HEAD requests. This makes sure the POST requests are always passed.
  #if (req.method != "GET" && req.method != "HEAD") {
  #  return (pass);
  #}

  # Dont Cache WordPress post pages and edit pages
    if (req.url ~ "(wp-admin|post\.php|edit\.php|wp-login)") {
        return(pass);
    }
    if (req.url ~ "/wp-cron.php" || req.url ~ "preview=true") {
        return (pass);
    }

  # Woocommerce
    if (req.url ~ "(cart|my-account|checkout|addons)") {
        return (pass);
    }
    if ( req.url ~ "\?add-to-cart=" ) {
        return (pass);
    }

  # Paid memberships Pro PMP
    if ( req.url ~ "(membership-account|membership-checkout)" ) {
        return (pass);
    }

  # WordPress Social Login Plugin. Note: Need to develop this. Please share if you have an example.
    if (req.url ~ "(wordpress-social-login|wp-social-login)") {
        return (pass);
    }

  # WP-Affiliate
    # if ( req.url ~ "\?ref=" ) {
    #     return (pass);
    # }

  # phpBB Logged in users and ACP
    if ( req.url ~ "(/forumPM/adm/|ucp.php?mode=|\?mode=edit)" ) {
        return (pass);
    }


###
###    http header Cookie
###    Remove some cookies (if found)
###    Cache This Stuff
###
# https://www.varnish-cache.org/docs/4.0/users-guide/increasing-your-hitrate.html#cookies

  ### COOKIE MADNESS.

  #   # Remove the "has_js" cookie
  #   set req.http.Cookie = regsuball(req.http.Cookie, "has_js=[^;]+(; )?", "");

  #   # Remove any Google Analytics based cookies
  #   set req.http.Cookie = regsuball(req.http.Cookie, "__utm.=[^;]+(; )?", "");
  #   set req.http.Cookie = regsuball(req.http.Cookie, "_ga=[^;]+(; )?", "");
  #   set req.http.Cookie = regsuball(req.http.Cookie, "utmctr=[^;]+(; )?", "");
  #   set req.http.Cookie = regsuball(req.http.Cookie, "utmcmd.=[^;]+(; )?", "");
  #   set req.http.Cookie = regsuball(req.http.Cookie, "utmccn.=[^;]+(; )?", "");

  #   # Remove the Quant Capital cookies (added by some plugin, all __qca)
  #   set req.http.Cookie = regsuball(req.http.Cookie, "__qc.=[^;]+(; )?", "");

  #   # Remove the wp-settings-1 cookie
  #   set req.http.Cookie = regsuball(req.http.Cookie, "wp-settings-1=[^;]+(; )?", "");

  #   # Remove the wp-settings-time-1 cookie
  #   set req.http.Cookie = regsuball(req.http.Cookie, "wp-settings-time-1=[^;]+(; )?", "");

  #   # Remove the wp test cookie
  #   set req.http.Cookie = regsuball(req.http.Cookie, "wordpress_test_cookie=[^;]+(; )?", "");

  #   # Remove the phpBB cookie. This will help us cache bots and anonymous users.
  #   set req.http.Cookie = regsuball(req.http.Cookie, "style_cookie=[^;]+(; )?", "");
  #   set req.http.Cookie = regsuball(req.http.Cookie, "phpbb3_psyfx_track=[^;]+(; )?", "");

  #   # Remove the cloudflare cookie
  #   set req.http.Cookie = regsuball(req.http.Cookie, "__cfduid=[^;]+(; )?", "");

  #   # Remove the PHPSESSID in members area cookie
  #   set req.http.Cookie = regsuball(req.http.Cookie, "PHPSESSID=[^;]+(; )?", "");

  #   # Are there cookies left with only spaces or that are empty?
  #   if (req.http.cookie ~ "^\s*$") {
  #   unset req.http.cookie;
  #   }

  # # MEGA DROP. Drop ALL cookies sent to WordPress, except those originating from the URLs defined.
  # # This increases HITs significantly, but be careful it can also break plugins that need cookies.
  # # Note: The /members/ directory had problems with PMP login and social login plugin.
  # # Adding it to the exclude list here (and including it below in the "Retain cookies" list) fixed login.
  # # This works better than than other cookie removal examples found on varnish's website.
  # # Note phpBB directory (forumPM) also passes cookies here.
  # if (!(req.url ~ "(wp-login|wp-admin|cart|my-account|checkout|addons|wordpress-social-login|wp-login\.php|forumPM|members)")) {
  # unset req.http.cookie;
  # }

  # Normalize the query arguments.
  # Note: Placing this above the "do not cache" section breaks some WP theme elements and admin functionality.
  # set req.url = std.querysort(req.url);

  # Large static files are delivered directly to the end-user without
  # waiting for Varnish to fully read the file first.
  # Varnish 4 fully supports Streaming, so see do_stream in vcl_backend_response() to witness the glory.
  if (req.url ~ "^[^?]*\.(mp[34]|rar|tar|tgz|wav|zip|bz2|xz|7z|avi|mov|ogm|mpe?g|mk[av])(\?.*)?$") {
    unset req.http.Cookie;
    return (hash);
  }

  # Cache all static files by Removing all cookies for static files
  # Remember, do you really need to cache static files that don't cause load? Only if you have memory left.
  # Here I decide to cache these static files. For me, most of them are handled by the CDN anyway.
  if (req.url ~ "^[^?]*\.(bmp|bz2|css|doc|eot|flv|gif|ico|jpeg|jpg|js|less|pdf|png|rtf|swf|txt|woff|xml)(\?.*)?$") {
    unset req.http.Cookie;
    return (hash);
  }

  # Cache all static files by Removing all cookies for static files - These file extensions are generated by WP Super Cache.
  if (req.url ~ "^[^?]*\.(html|htm|gz)(\?.*)?$") {
    unset req.http.Cookie;
    return (hash);
  }

  # Do not cache Authorized requests.
    if (req.http.Authorization) {
        return(pass);
    }

 # Cache all others requests.
 # Note Varnish v4: vcl_recv must now return hash instead of lookup
    return (hash);
}


