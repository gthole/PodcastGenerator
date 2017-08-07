<?php
/**
 * Download analytics
 */

require_once(dirname(__FILE__).'/ss-ga.class.php');
include("config.php");

// Snag just the domain name
$pg_domain = preg_replace('/\//', '', preg_replace('/https?:\/\//', '', $url));

// Set up the ssga client
$ssga = new ssga($podcast_ga_id, $pg_domain);

if(isset($_GET['url']))
{
    // Get the episode name off the url
    $name = $_GET['name'];
    $name = urldecode($name);
    $name = filter_var($name, FILTER_SANITIZE_URL);

    // Check that the file exists before sending
    if ( file_exists($absoluteURL.$upload_dir.$name) )
    {
        // Post to GA
        $ssga->set_event('Downloads', 'Download Type', $name);
        $ssga->send();
        $ssga->reset();

        // And redirect to the full file
        header('Location: ' . $url.$upload_dir.$name);
    }
    else
    {
        trigger_error('Sorry, this file is invalid.');
    }
}
else
{
    trigger_error('Sorry, you didn\'t specify a URL to download.');
}

