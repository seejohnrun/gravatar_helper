<?php // if (!defined('BASEPATH')) exit('No direct script access allowed');

// A helper for generating gravatar links
class Gravatar_helper {

    // The gravatar base URL
    private static $image_base_url = 'http://gravatar.com/avatar.php';
    private static $profile_base_url = 'http://gravatar.com/';

    /*
     * Generate a gravatar link from an email address
     *
     * $email: The email to generate the link for
     * +++ all the other arguments for gravatar_hash()
     */
    static function from_email($email, $rating = null, $size = null, $default = null) {
        return self::from_hash(md5($email), $rating, $size, $default);
    }

    /*
     * Generate a gravatar link from an email hash
     *
     * $hash: the hash to generate the link for
     * $rating: the rating ('G', 'R', 'X')
     * $size: The size (square) of the desired image
     * $default: The default image if the user doesn't have one
     */
    static function from_hash($hash, $rating = null, $size = null, $default = null) {
        // Add the gravatar id
        $options = array();
        $options[] = "gravatar_id=$hash";
        // optional options
        if ($rating) $options[] = "rating=$rating";
        if ($size) $options[] = "size=$size";
        if ($default) $options[] = "default=$default";
        // put together the URL and return it
        return self::$image_base_url . '?' . implode($options, '&');
    }

    /*
     * Get the profile of a user by email, or null if not found
     *
     * $email: the email to fetch the profile for
     */
    static function profile_from_email($email) {
        return self::profile_from_hash(md5($email));
    }

    /*
     * Get the profile of a user by email hash, or null if not found
     *
     * $hash: the hash to fetch the profile for
     */
    static function profile_from_hash($hash) {
        $opts = array(
            'http' => array(
                'method' => 'GET',
                'user_agent' => 'PHP GravatarHelper'
            )
        );
        $context = stream_context_create($opts);
        try {
            $raw = file_get_contents(self::$profile_base_url . $hash . '.json', false, $context);
        } catch (Exception $e) {
            return null;
        }
        if ($raw) {
            $data = json_decode($raw);
            $entry = $data->entry;
            return $entry[0];
        } else {
            return null;
        }
    }

}
